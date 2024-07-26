<?php

namespace App\Http\Resources\Course;

use App\Http\Resources\Course\Mobile\LectureResource;
use App\Http\Resources\Course\Web\CourseSubscribedExamResource;
use Illuminate\Http\Resources\Json\JsonResource;

class WebCoursePinResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'type' => "LECTURE",
            'id' => $this->lecture->id,
            'name' => $this->lecture->t('name'),
            'description' => $this->lecture->t('description'),
            'duration' => $this->lecture->duration,
            'is_watched' => auth('api')?->user()->watchedLectures->contains('lecture_id', $this->lecture->id) ?? false,
            'lecture_type' => $this->lecture->type,
            'live_link' => $this->lecture->live_link,
            'link_platform' => $this->lecture->link_platform,
            'record_link' => $this->lecture->record_link,
            'pins' => LecturePinResource::collection($this->lecture->pins),
            'exams' => CourseSubscribedExamResource::collection(($this->lecture->exams()->where('is_active', true)->whereHas('questions', fn ($query) => $query->where('is_active', true))->get())),
            'pin_name' => $this->t('name'),
            'time' => $this->time
        ];
    }
}
