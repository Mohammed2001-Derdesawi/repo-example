<?php

namespace App\Http\Services\Api\Course;

use App\Http\Requests\Api\Course\AskTeacherRequest;
use App\Http\Requests\Api\Course\CertificateRequest;
use App\Http\Resources\Course\CourseAttachmentResource;
use App\Http\Resources\Course\CourseBookResource;
use App\Http\Resources\Course\CourseBookSolutionResource;
use App\Http\Resources\Course\CourseChannelResource;
use App\Http\Resources\Course\CourseInquireResource;
use App\Http\Resources\Course\CourseResource;
use App\Http\Resources\Course\CourseUnsubscribedExamResource;
use App\Http\Resources\Course\Mobile\CourseAboutResource;
use App\Http\Resources\Course\Mobile\CourseContentResource;
use App\Http\Resources\Course\Mobile\CourseNotSubscribedResource;
use App\Http\Resources\Course\Mobile\CourseSectionResource;


class MobileCourseService extends CourseService
{

    public function show($id){
        return $this->get->handle(CourseNotSubscribedResource::class , $this->courseRepository , 'getActiveById',
            [$id ,['*'] ,  ['teachers:id,name,image,cv_description' , 'lectures']] ,
            true);
    }

    public function unSubscribedExams($id){
        return $this->get->handle(CourseUnsubscribedExamResource::class , $this->courseRepository , 'getUnSubscribesCourseExams',
            [$id] ,
            false);
    }

    public function showSubscribed($id){

        return $this->get->handle(CourseContentResource::class , $this->courseRepository , 'getActiveById',
            [$id ,['*'] ,  ['teachers:id,name,image,cv_description' , 'lectures']] ,
            true);
    }

    public function getCourseBooks($id){
        return $this->get->handle(CourseBookResource::class , $this->courseBookRepository , 'getCourseBooks',
            [$id] ,
            false);
    }

    public function getCourseAttachments($id){
        return $this->get->handle(CourseAttachmentResource::class , $this->courseAttachmentRepository , 'getCourseAttachments',
            [$id] ,
            false);
    }



    public function getCourseChannels($id){
        return $this->get->handle(CourseChannelResource::class , $this->courseRepository , 'getById',
            [$id , ['id' , 'whatsapp_link' , 'telegram_link' , 'telegram_channel_link']] ,
            true);
    }

    public function getCourseInquiries($id){
        return $this->get->handle(CourseInquireResource::class , $this->courseInquireRepository , 'getCourseInquiries',
            [$id] ,
            false);
    }

    public function askQuestion($id , AskTeacherRequest $request){
        return $this->courseService->askTeacher($id , $request);
    }

    public function requestCertificate(CertificateRequest $request) {
        return $this->certificate->request($request);
    }


    public function getCourseBooksSolutions($id){
        return $this->get->handle(CourseBookSolutionResource::class , $this->courseBookSolutionRepository , 'getCourseBooksSolutions',
            [$id] ,
            false);
    }


    public function importantCourses(){
        return $this->get->handle(CourseResource::class , $this->courseRepository , 'getImportantCourses',
            [] ,
            false);
    }

    public function showAbout($id)
    {
        return $this->get->handle(CourseAboutResource::class, $this->courseRepository, 'getById', [$id, ['*'], ['teachers']], true);
    }

    public function whatToShow($id)
    {
        return $this->get->handle(CourseSectionResource::class, $this->courseRepository, 'getById', [$id], true);
    }
}
