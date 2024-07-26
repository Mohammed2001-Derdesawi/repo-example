<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseBookSolutionResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->t('name'),
            'video_platform' => $this->solution_video_platform,
            'video_link' => $this->solution_video_link
        ];
    }
}
