<?php

namespace App\Http\Resources\Course;

use App\Http\Resources\Course\Mobile\LectureResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MobileCoursePinResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'lecture' => new LectureResource($this->lecture),
            'name' => $this->t('name'),
            'time' => $this->time
        ];
    }
}
