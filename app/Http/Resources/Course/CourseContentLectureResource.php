<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseContentLectureResource extends JsonResource
{

    public function toArray($request)
    {
        return [
          'name' => $this->t('name'),
        ];
    }
}
