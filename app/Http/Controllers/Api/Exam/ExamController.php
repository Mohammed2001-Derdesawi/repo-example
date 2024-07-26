<?php

namespace App\Http\Controllers\Api\Exam;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Exam\EndExamUserRequest;
use App\Http\Requests\Api\Exam\ExamRequest;
use App\Http\Requests\Api\Exam\ExamUserRequest;
use App\Http\Resources\Exam\ExamUserResource;
use App\Http\Services\Api\Exam\ExamService;
use App\Http\Services\Mutual\GetService;
use App\Repository\ExamUserRepositoryInterface;

class ExamController extends Controller
{
    protected ExamService $exam;
    public function __construct(
        ExamService $examService,
    )
    {
        $this->middleware('auth:api');
        $this->exam = $examService;
    }

    public function start(ExamRequest $request) {
        return $this->exam->start($request);
    }

    public function perform(ExamUserRequest $request) {
        return $this->exam->perform($request);
    }

    public function end(EndExamUserRequest $request) {
        return $this->exam->end($request);
    }

    public function show(ExamUserRequest $request) {
        return $this->exam->show($request);
    }

    public function result(ExamUserRequest $request) {
        return $this->exam->result($request);
    }


    public function past() {
        return $this->exam->past();
    }

    public function examAttempts($exam_id){
        return $this->exam->examAttempts($exam_id);
    }
}
