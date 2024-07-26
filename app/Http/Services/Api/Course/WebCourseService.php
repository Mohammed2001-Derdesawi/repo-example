<?php

namespace App\Http\Services\Api\Course;

use App\Http\Requests\Api\Course\EmbedExamIntroductoryVideoRequest;
use App\Http\Requests\Api\Course\EmbedIntroductoryVideoRequest;
use App\Http\Requests\Api\Course\EmbedLectureRequest;
use App\Http\Requests\Api\Course\EmbedSolutionVideoRequest;
use App\Http\Resources\Course\CourseDetailsResource;
use App\Http\Resources\Course\ExamIntroductoryVideoEmbedResource;
use App\Http\Resources\Course\CourseExamsByTypeResource;
use App\Http\Resources\Course\CourseIntroductoryVideoEmbedResource;
use App\Http\Resources\Course\CourseLectureEmbedResource;
use App\Http\Resources\Course\CourseLectureSimpleResource;
use App\Http\Resources\Course\SolutionVideoEmbedResource;
use App\Http\Resources\Course\Web\CourseContentResource;

class WebCourseService extends CourseService
{

    public function show($id){
        return $this->get->handle(CourseDetailsResource::class , $this->courseRepository , 'getActiveById',
            [$id ,['*'] ,  ['teachers:id,name,image,cv_description' , 'lectures' , 'books:id,course_id,name_ar,name_en' , 'exams']] ,
            true);
    }

    public function showSubscribed($id){
        return $this->get->handle(CourseContentResource::class , $this->courseRepository , 'getActiveById',
            [$id ,['*'] ,  ['teachers:id,name,image,cv_description' , 'lectures']] ,
            true);
    }

    public function getCourseExamsByType($course_id , $type){
        return $this->get->handle(CourseExamsByTypeResource::class , $this->examRepository , 'getCourseExamsByType',
            [$course_id , $type]);
    }

    public function embedLecture(EmbedLectureRequest $request) {
        if ($this->lectureRepository->isAuthorizedToEmbed($request->lecture_id)) {
            $lecture = $this->lectureRepository->getById($request->lecture_id);
            return $this->responseSuccess(data: new CourseLectureEmbedResource($lecture));
        } else {
            return $this->responseFail(message: __('messages.You are not authorized to embed this lecture'));
        }
    }

    public function embedIntroductoryVideo(EmbedIntroductoryVideoRequest $request) {
        $course = $this->courseRepository->getById($request->course_id);
        if ($course->explanation_video !== null && $course->explanation_video_platform !== null) {
            return $this->responseSuccess(data: new CourseIntroductoryVideoEmbedResource($course));
        } else {
            return $this->responseFail(message: __('messages.You are not authorized to embed this introductory video'));
        }
    }

    public function embedExamIntroductoryVideo(EmbedExamIntroductoryVideoRequest $request) {
        $exam = $this->examRepository->getById($request->exam_id);
        if ($exam->solution_video_link !== null && $exam->solution_video_platform !== null) {
            return $this->responseSuccess(data: new ExamIntroductoryVideoEmbedResource($exam));
        } else {
            return $this->responseFail(message: __('messages.There is no solution video'));
        }
    }

    public function embedBookSolutionVideo(EmbedSolutionVideoRequest $request) {
        $bookSolution = $this->courseBookSolutionRepository->getById($request->book_solution_id);
        if ($bookSolution->solution_video_link !== null && $bookSolution->solution_video_platform !== null) {
            return $this->responseSuccess(data: new SolutionVideoEmbedResource($bookSolution));
        } else {
            return $this->responseFail(message: __('messages.There is no solution video'));
        }
    }
}
