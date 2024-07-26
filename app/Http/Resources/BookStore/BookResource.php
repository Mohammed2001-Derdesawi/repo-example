<?php

namespace App\Http\Resources\BookStore;

use App\Http\Resources\Course\CourseTeacherResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->t('name'),
            'description' => $this->t('description'),
            'image' => $this->course !== null ? $this->course->image : $this->image,
            'course' => $this->course?->t('name')?? null,
            'price' => $this->price,
            'is_printed_before' => $this->is_printed_before,
            'is_purchased_before' => $this->is_purchased_before,
        ];
    }
}
