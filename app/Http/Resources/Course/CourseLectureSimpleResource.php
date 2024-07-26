<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseLectureSimpleResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'name' => $this->t('name'),
            'duration' => $this->duration,
            'is_free' => $this->is_free,
            'link_platform' => $this->link_platform,
            'video_link' => $this->standard?->course?->isSubscribed() || $this->is_free ? $this->record_link : null,
            'exams' => CourseUnsubscribedExamResource::collection($this->exams->where('is_active' , true))
        ];
    }
}
