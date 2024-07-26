<?php

namespace App\Http\Services\Api\Course;

use App\Http\Requests\Api\Course\AskTeacherRequest;
use App\Http\Requests\Api\Course\EmbedLectureRequest;
use App\Http\Requests\Api\Course\RateCourseRequest;
use App\Http\Resources\Course\CourseAttachmentResource;
use App\Http\Resources\Course\CourseBookResource;
use App\Http\Resources\Course\CourseBookSolutionResource;
use App\Http\Resources\Course\CourseChannelResource;
use App\Http\Resources\Course\CourseCollection;
use App\Http\Resources\Course\CourseCommonQuestionsResource;
use App\Http\Resources\Course\CourseExamsByTypeResource;
use App\Http\Resources\Course\CourseInquireResource;
use App\Http\Resources\Course\CourseResource;
use App\Http\Resources\Course\Web\CourseExamResource;
use App\Http\Services\Api\Cart\CartService;
use App\Http\Services\Api\Certificate\CertificateService;
use App\Http\Services\Mutual\FileManagerService;
use App\Http\Services\Mutual\GetService;
use App\Http\Traits\Responser;
use App\Repository\CourseAttachmentRepositoryInterface;
use App\Repository\CourseBookRepositoryInterface;
use App\Repository\CourseBookSolutionRepositoryInterface;
use App\Repository\CourseInquireRepositoryInterface;
use App\Repository\CourseRepositoryInterface;
use App\Repository\CourseReviewRepositoryInterface;
use App\Repository\Eloquent\LectureRepository;
use App\Repository\ExamRepositoryInterface;
use App\Repository\FieldRepositoryInterface;
use App\Repository\LectureRepositoryInterface;
use Illuminate\Support\Facades\DB;

abstract class CourseService
{
    use Responser;
    protected CourseRepositoryInterface $courseRepository;
    protected CartService $cartService;
    protected CourseInquireRepositoryInterface $courseInquireRepository;
    protected CourseReviewRepositoryInterface $courseReviewRepository;
    protected FileManagerService $fileManager;
    protected GetService $get;
    protected CourseBookRepositoryInterface $courseBookRepository;
    protected CourseAttachmentRepositoryInterface $courseAttachmentRepository;
    protected FieldRepositoryInterface $fieldRepository;
    protected CourseBookSolutionRepositoryInterface $courseBookSolutionRepository;
    protected ExamRepositoryInterface $examRepository;
    protected LectureRepositoryInterface $lectureRepository;
    protected CertificateService $certificate;

    public function __construct(
        CourseRepositoryInterface $courseRepository ,
        CartService $cartService ,
        CourseInquireRepositoryInterface $courseInquireRepository,
        CourseReviewRepositoryInterface $courseReviewRepository,
        FileManagerService $fileManager,
        GetService $get,
        CourseBookRepositoryInterface $courseBookRepository,
        CourseAttachmentRepositoryInterface $courseAttachmentRepository,
        FieldRepositoryInterface $fieldRepository,
        CourseBookSolutionRepositoryInterface $courseBookSolutionRepository,
        ExamRepositoryInterface $examRepository,
        CertificateService $certificate,
        LectureRepositoryInterface $lectureRepository,
    ){
        $this->courseRepository = $courseRepository;
        $this->cartService = $cartService;
        $this->courseInquireRepository = $courseInquireRepository;
        $this->courseReviewRepository = $courseReviewRepository;
        $this->fileManager = $fileManager;
        $this->get = $get;
        $this->courseBookRepository = $courseBookRepository;
        $this->courseAttachmentRepository = $courseAttachmentRepository;
        $this->fieldRepository = $fieldRepository;
        $this->courseBookSolutionRepository = $courseBookSolutionRepository;
        $this->examRepository = $examRepository;
        $this->certificate = $certificate;
        $this->lectureRepository = $lectureRepository;
    }


    public function filter(){
        return $this->get->handle(CourseCollection::class , $this->courseRepository , 'filterCourses' ,
            [9 , ['id' , 'category_id' , 'price' ,'app_price','image' , 'name_ar' , 'name_en' , 'show_teacher_names']] ,
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

    public function getCourseExams($id){
        return $this->get->handle(CourseExamResource::class , $this->examRepository , 'getCourseExams',
            [$id]);
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



    public function getCourseBooksSolutions($id){
        return $this->get->handle(CourseBookSolutionResource::class , $this->courseBookSolutionRepository , 'getCourseBooksSolutions',
            [$id] ,
            false);
    }


    public function getCommonQuestions($id) {
        return $this->get->handle(CourseCommonQuestionsResource::class, $this->fieldRepository, 'getCommonQuestions', [$id] , false);
    }

    public function importantCourses(){
        return $this->get->handle(CourseResource::class , $this->courseRepository , 'getImportantCourses',
            [] ,
            false);
    }


    public function checkSubscribe($id){
        if (auth('api')->user()->courses->contains('id', $id))
            return $this->responseSuccess(200 , true);
        return $this->responseFail(422 , false);

    }

    public function askTeacher($id , AskTeacherRequest $request){
        DB::beginTransaction();
        try {
            $data = $request->validated();
            if ($request->has('attachment'))
                $data['attachment'] = $this->fileManager->handle('attachment', 'courses/inquiries/');
            $data['user_id'] = auth('api')->user()->id;
            $data['course_id'] = $id;
            $this->courseInquireRepository->create($data);
            DB::commit();
            return $this->responseSuccess(200  , __('messages.question sent'));
        }catch (\Exception $e){
            DB::rollBack();
            return $this->responseFail(422 , __('messages.Something went wrong'));

        }
    }


    public function rateCourse(RateCourseRequest $request){
        if (auth('api')->user()->reviews->contains('course_id' , $request['course_id']))
            return $this->responseFail(422 , __('messages.Course_Already_rated'));
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $data['user_id'] = auth('api')->user()->id;
            $this->courseReviewRepository->create($data);
            DB::commit();
            return $this->responseSuccess(200  , __('messages.rate_sent'));
        }catch (\Exception $e){
            DB::rollBack();
            return $this->responseFail(422 , __('messages.Something went wrong'));

        }
    }
}
