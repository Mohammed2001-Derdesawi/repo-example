<?php

namespace App\Http\Resources\Course\Web;

use App\Http\Resources\Course\CourseAttachmentResource;
use App\Http\Resources\Course\CourseBookResource;
use App\Http\Resources\Course\CourseBookSolutionResource;
use App\Http\Resources\Course\CourseChannelResource;
use App\Http\Resources\Course\CourseInquireResource;
use App\Http\Resources\Course\MobileCoursePinResource;
use App\Http\Resources\Course\CourseTeacherResource;
use App\Http\Resources\Course\WebCoursePinResource;
use App\Http\Resources\Lecture\PinResource;
use App\Repository\CourseAttachmentRepositoryInterface;
use App\Repository\CourseBookRepositoryInterface;
use App\Repository\CourseBookSolutionRepositoryInterface;
use App\Repository\CourseInquireRepositoryInterface;
use App\Repository\ExamRepositoryInterface;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseContentResource extends JsonResource
{

    public function toArray($request)
    {
        $bookRepository = app(CourseBookRepositoryInterface::class);
        $attachmentRepository = app(courseAttachmentRepositoryInterface::class);
        $examRepository = app(ExamRepositoryInterface::class);
        $inquiriesRepository = app(courseInquireRepositoryInterface::class);
        $bookSolutionRepository = app(courseBookSolutionRepositoryInterface::class);
        return [
            'name' => $this->t('name'),
            'description' => $this->t('description'),
            'course_content' => $this->courseContent() ,
            'new_course_content' => $this->newCourseContent(),
            'teachers' => CourseTeacherResource::collection($this->teachers),
            'progress' => $this->isSubscribed() ? $this->user_progress : null,
            'rating' => $this->rating(),
            'currentUserRating' => $this->currentUserRating(),
            'request_certificate_available' => $this->request_certificate_available,
            'has_request_certificate'=>$this->isRequestCertificate(),
            'books' => CourseBookResource::collection($bookRepository->getCourseBooks($this->id)),
            'attachments' => CourseAttachmentResource::collection($attachmentRepository->getCourseAttachments($this->id)),
            'exams' => CourseExamResource::collection($examRepository->getCourseExams($this->id)),
            'channels' => CourseChannelResource::make($this),
            'inquiries' => CourseInquireResource::collection($inquiriesRepository->getCourseInquiries($this->id)),
            'booksSolutions' => CourseBookSolutionResource::collection($bookSolutionRepository->getCourseBooksSolutions($this->id)),
            'pins' => WebCoursePinResource::collection($this->pins),
        ];
    }
}
