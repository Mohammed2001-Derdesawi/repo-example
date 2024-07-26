<?php

namespace App\Http\Controllers\Api\Lecture;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Course\CompleteLectureRequest;
use App\Http\Services\Api\Lecture\LectureService;

class LectureController extends Controller
{

    private LectureService $lectureService;


    public function __construct(LectureService $lectureService){
        $this->lectureService = $lectureService;
        $this->middleware('auth:api')->except('lecturePins');
    }

    public function showLecture($id){
        return $this->lectureService->showLecture($id);
    }

    public function completeLecture(CompleteLectureRequest $request){
        return $this->lectureService->completeWatch($request['lecture_id']);
    }

    public function lecturePins($id){
        return $this->lectureService->lecturePins($id);
    }
}
