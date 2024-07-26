<?php

namespace App\Http\Resources\Course\Mobile;

use App\Http\Resources\Course\MobileCoursePinResource;
use App\Http\Resources\Course\LecturePinResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseContentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->t('name'),
//            'description' => $this->t('description'),
            'rating' => $this->rating(),
            'is_ratable' => (bool) $this->is_ratable,
            'progress' => $this->isSubscribed() ? $this->user_progress : null,
            'currentUserRating' => $this->currentUserRating(),
            'request_certificate_available' => $this->request_certificate_available,
            'has_request_certificate'=>$this->isRequestCertificate(),
            'certificate_price' => $this->certificate_price,
            'lectures' => LectureResource::collection($this->lectures()->watchable()->get()),
            'new_course_content' => $this->newCourseContent(),
            'pins' => MobileCoursePinResource::collection($this->pins),
        ];
    }
}
