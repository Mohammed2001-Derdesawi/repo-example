<?php

namespace App\Http\Controllers\Api\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Course\AskTeacherRequest;
use App\Http\Requests\Api\Course\CertificateRequest;
use App\Http\Requests\Api\Course\EmbedExamIntroductoryVideoRequest;
use App\Http\Requests\Api\Course\EmbedIntroductoryVideoRequest;
use App\Http\Requests\Api\Course\EmbedLectureRequest;
use App\Http\Requests\Api\Course\EmbedSolutionVideoRequest;
use App\Http\Requests\Api\Course\RateCourseRequest;
use App\Http\Services\Api\Certificate\CertificateService;
use App\Http\Services\Api\Course\CourseService;
use App\Http\Services\Mutual\GetService;

class CourseController extends Controller
{
    protected CourseService $courseService;
    protected GetService $get;

    protected CertificateService $certificate;

    public function __construct(
        CourseService $courseService ,
        GetService $getService ,
        CertificateService $certificateService,
    ){
        $this->courseService = $courseService;
        $this->get = $getService;
        $this->certificate = $certificateService;
        $this->middleware('auth:api')->except(['index' , 'filter' , 'show' ,'showMobile' , 'importantCourses' , 'unSubscribedExams', 'embedLecture', 'embedIntroductoryVideo', 'embedExamIntroductoryVideo', 'embedBookSolutionVideo']);
        $this->middleware('UserSubscribedToCourse')->only(['showSubscribed' , 'showSubscribedMobile'  , 'getCourseBooks' ,
            'getCourseAttachments' , 'getCourseExams' , 'getCourseChannels' , 'getCourseInquiries' , 'askQuestion', 'showAbout', 'whatToShow']);
    }

    public function filter(){
        return $this->courseService->filter();
    }

    public function importantCourses(){
        return $this->courseService->importantCourses();
    }

    public function checkSubscribe($id){
        return $this->courseService->checkSubscribe($id);
    }

    public function show($id){
        return $this->courseService->show($id);
    }

    public function showSubscribed($id){
        return $this->courseService->showSubscribed($id);
    }


    public function unSubscribedExams($id){
        return $this->courseService->unSubscribedExams($id);
    }

    public function getCourseExams($id){
        return $this->courseService->getCourseExams($id);
    }

    public function getCourseExamsByType($id , $type){
        return $this->courseService->getCourseExamsByType($id , $type);
    }


    public function getCourseBooks($id){
        return $this->courseService->getCourseBooks($id);
    }

    public function getCourseAttachments($id){
        return $this->courseService->getCourseAttachments($id);
    }

    public function getCourseChannels($id){
        return $this->courseService->getCourseChannels($id);
    }

    public function getCourseInquiries($id){
        return $this->courseService->getCourseInquiries($id);
    }

    public function askQuestion($id , AskTeacherRequest $request){
        return $this->courseService->askTeacher($id , $request);
    }

    public function getCourseBooksSolutions($id){
        return $this->courseService->getCourseBooksSolutions($id);
    }

    public function getCommonQuestions($id) {
        return $this->courseService->getCommonQuestions($id);
    }

    public function rateCourse(RateCourseRequest $request){
        return $this->courseService->rateCourse($request);
    }

    public function requestCertificate(CertificateRequest $request) {
        return $this->certificate->request($request);
    }

    public function embedLecture(EmbedLectureRequest $request) {
        return $this->courseService->embedLecture($request);
    }

    public function embedIntroductoryVideo(EmbedIntroductoryVideoRequest $request) {
        return $this->courseService->embedIntroductoryVideo($request);
    }

    public function embedExamIntroductoryVideo(EmbedExamIntroductoryVideoRequest $request) {
        return $this->courseService->embedExamIntroductoryVideo($request);
    }

    public function embedBookSolutionVideo(EmbedSolutionVideoRequest $request)
    {
        return $this->courseService->embedBookSolutionVideo($request);
    }

    public function showAbout($id)
    {
        return $this->courseService->showAbout($id);
    }

    public function whatToShow($id)
    {
        return $this->courseService->whatToShow($id);
    }

}
