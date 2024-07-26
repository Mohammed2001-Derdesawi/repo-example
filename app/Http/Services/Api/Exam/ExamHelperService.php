<?php

namespace App\Http\Services\Api\Exam;

use App\Http\Requests\Api\Exam\ExamUserRequest;
use App\Http\Resources\Exam\PerformExamResource;
use App\Http\Resources\Exam\StartExamResource;
use App\Http\Services\Mutual\GetService;
use App\Http\Traits\Responser;
use App\Repository\AnswerRepositoryInterface;
use App\Repository\AnswerUserRepositoryInterface;
use App\Repository\ExamRepositoryInterface;
use App\Repository\ExamUserRepositoryInterface;
use App\Repository\QuestionRepositoryInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ExamHelperService
{
    use Responser;

    private ExamRepositoryInterface $examRepository;
    private ExamUserRepositoryInterface $examUserRepository;
    private QuestionRepositoryInterface $questionRepository;
    private AnswerRepositoryInterface $answerRepository;
    private AnswerUserRepositoryInterface $answerUserRepository;
    protected GetService $get;

    public function __construct(
        ExamRepositoryInterface $examRepository,
        ExamUserRepositoryInterface $examUserRepository,
        QuestionRepositoryInterface $questionRepository,
        AnswerRepositoryInterface $answerRepository,
        AnswerUserRepositoryInterface $answerUserRepository,
        GetService $getService,
    )
    {
        $this->examRepository = $examRepository;
        $this->examUserRepository = $examUserRepository;
        $this->questionRepository = $questionRepository;
        $this->answerRepository = $answerRepository;
        $this->answerUserRepository = $answerUserRepository;
        $this->get = $getService;
    }

    public function initiateExam($exam, $userId) {
        try {
            DB::beginTransaction();
            $questions = $this->questionRepository->getByExam($exam->id);
            $examUser = $this->examUserRepository->create([
                'user_id' => $userId,
                'exam_id' => $exam->id,
                'ends_at' => Carbon::now()->addSeconds(round($exam->duration * 60))
            ]);
            $questions = array_map(function ($question) use ($examUser, $userId) {
                return [
                    'user_id' => $userId,
                    'question_id' => $question['id'],
                    'exam_user_id' => $examUser->id
                ];
            }, $questions->toArray());
            $this->answerUserRepository->createMany($questions);
            DB::commit();
            return $this->responseSuccess(message: __('messages.Exam Started Successfully'), data: new StartExamResource($examUser));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->responseFail(message: __('messages.Something went wrong'));
        }
    }

    public function correct($examUser, $answers) {
        foreach ($answers as $answer) {
            $this->answerUserRepository->correct($examUser, $answer);
        }
    }

    public function get(ExamUserRequest $request, $action, $resource) {
        $examId = $request->user_exam_id;
        $examUser = $this->examUserRepository->getById($examId);
        if (Gate::allows($action.'-exam', $examUser)) {
            return $this->responseSuccess(data: new PerformExamResource($examUser, $this->answerUserRepository));
        } else {
            return $this->responseFail(status: 401, message: __('messages.You are not authorized to ' . $action . ' this exam'));
        }
    }
}
