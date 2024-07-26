<?php

namespace App\Http\Services\Api\Exam;

use App\Http\Requests\Api\Exam\EndExamUserRequest;
use App\Http\Requests\Api\Exam\ExamRequest;
use App\Http\Requests\Api\Exam\ExamUserRequest;
use App\Http\Resources\Exam\ExamAttemptsResource;
use App\Http\Resources\Exam\ExamResultResource;
use App\Http\Resources\Exam\ExamUserResource;
use App\Http\Resources\Exam\PastExamResource;
use App\Http\Resources\Exam\PerformExamResource;
use App\Http\Services\Mutual\GetService;
use App\Http\Traits\Responser;
use App\Repository\AnswerRepositoryInterface;
use App\Repository\AnswerUserRepositoryInterface;
use App\Repository\ExamRepositoryInterface;
use App\Repository\ExamUserRepositoryInterface;
use App\Repository\QuestionRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

abstract class ExamService
{
    use Responser;

    protected ExamRepositoryInterface $examRepository;
    protected ExamUserRepositoryInterface $examUserRepository;
    protected QuestionRepositoryInterface $questionRepository;
    protected AnswerRepositoryInterface $answerRepository;
    protected AnswerUserRepositoryInterface $answerUserRepository;
    protected GetService $get;
    protected ExamHelperService $examHelper;

    public function __construct(
        ExamRepositoryInterface $examRepository,
        ExamUserRepositoryInterface $examUserRepository,
        QuestionRepositoryInterface $questionRepository,
        AnswerRepositoryInterface $answerRepository,
        AnswerUserRepositoryInterface $answerUserRepository,
        GetService $getService,
        ExamHelperService $examHelperService,
    )
    {
        $this->examRepository = $examRepository;
        $this->examUserRepository = $examUserRepository;
        $this->questionRepository = $questionRepository;
        $this->answerRepository = $answerRepository;
        $this->answerUserRepository = $answerUserRepository;
        $this->get = $getService;
        $this->examHelper = $examHelperService;
    }

    public function start(ExamRequest $request) {
        $exam = $this->examRepository->getById($request->exam_id);
        $userId = auth('api')->id();
//        if (Gate::allows('start-exam', $exam)) {
            if (!auth('api')->user()->courses()?->where('course_id', $exam?->course_id)?->exists()
                && !$exam->is_free
            ) {
                return $this->responseFail(status: 401, message: __('messages.You are not subscribed to the course of this exam'));
            }

            if (auth('api')->user()->exams()?->where('exam_id', $exam?->id)?->where('is_ended', false)?->exists()) {
                return $this->responseFail(status: 401, message: __('messages.You have the same exam before not ended yet'));
            }

//            if ($exam?->attempts < auth('api')->user()?->exams()?->where('exam_id', $exam->id)->count()) {
//                return $this->responseFail(status: 401, message: __('messages.The number of attempts available to enter the test has expired'));
//            }

            return $this->examHelper->initiateExam($exam, $userId);
//        } else {
//            return $this->responseFail(status: 401, message: __('messages.You are not authorized to start this exam'));
//        }
    }

    public function perform(ExamUserRequest $request) {
        return $this->examHelper->get($request, 'perform', PerformExamResource::class);
    }

    public function end(EndExamUserRequest $request) {
        $examUser = $this->examUserRepository->getById($request->user_exam_id);
        if (Gate::allows('perform-exam', $examUser)) {
            DB::transaction(function () use ($request, $examUser) {
                $this->examUserRepository->update($request->user_exam_id, ['is_ended' => true, 'ends_at' => Carbon::now()]);
                $this->examHelper->correct($examUser, $request['user_answers']);
            });
            return $this->responseSuccess(message: __('messages.Exam ended successfully'));
        } else {
            return $this->responseFail(status: 401, message: __('messages.You are not authorized to end this exam'));
        }
    }

    public function show(ExamUserRequest $request) {
        return $this->examHelper->get($request, 'show', PerformExamResource::class);
    }

    public function result(ExamUserRequest $request) {
        $examId = $request->user_exam_id;
        $examUser = $this->examUserRepository->getById($examId);
        if (Gate::allows('show-exam', $examUser)) {
            return $this->responseSuccess(data: new ExamResultResource($examUser, $this->answerUserRepository));
        } else {
            return $this->responseFail(status: 401, message: __('messages.You are not authorized to show this exam'));
        }
    }


    public function past() {

        return $this->get->handle(PastExamResource::class, $this->examRepository, 'getUserExams' , [auth('api')->id()]);
    }

    public function examAttempts($exam_id) {

        return $this->get->handle(ExamAttemptsResource::class, $this->examRepository, 'getById', [$exam_id] , is_instance: true );
    }
}
