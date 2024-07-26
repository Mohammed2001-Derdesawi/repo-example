<?php

namespace App\Http\Resources\Course;

use App\Http\Resources\Course\Mobile\LectureResource;
use Illuminate\Http\Resources\Json\JsonResource;

class LecturePinResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'lecture_id' => $this->lecture_id,
            'name' => $this->t('name'),
            'time' => $this->time
        ];
    }
}
