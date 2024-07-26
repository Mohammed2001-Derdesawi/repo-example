<?php

namespace App\Http\Resources\Course\Mobile;

use App\Http\Resources\Course\CourseExamsByTypeResource;
use App\Http\Resources\Course\LecturePinResource;
use Illuminate\Http\Resources\Json\JsonResource;

class LectureResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->t('name'),
            'description' => $this->t('description'),
            'duration' => $this->duration,
            'is_watched' => auth('api')?->user()?->watchedLectures->contains('lecture_id', $this->id) ?? false,
            'lecture_type' => $this->type,
            'live_link' => $this->live_link,
            'link_platform' => $this->link_platform,
            'record_link' => $this->record_link,
            'pins' => LecturePinResource::collection($this->pins),
            'exams' => CourseExamsByTypeResource::collection(($this->exams()->where('is_active', true)->whereHas('questions', fn ($query) => $query->where('is_active', true))->get())),
            // $this->exams[0]->id ?? null
        ];
    }
}
