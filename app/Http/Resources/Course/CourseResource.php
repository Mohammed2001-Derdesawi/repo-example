<?php

namespace App\Http\Resources\Course;

use App\Http\Resources\Course\CourseTeacherResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->t('name'),
            'image' => $this->image,
            'field' => $this->category->field->t('name'),
            'price' => $this->getRawOriginal('price'),
            'app_price' => $this->app_price,
            'show_teacher_names' => $this->show_teacher_names,
            'teachers' => CourseTeacherResource::collection($this->teachers),
            'isSubscribed' => $this->isSubscribed(),
            'rating' => $this->rating(),
            'progress' => $this->isSubscribed() ? $this->user_progress : null
        ];
    }
}
