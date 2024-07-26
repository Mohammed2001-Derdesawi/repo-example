<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseCommonQuestionsResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'question' => $this['title_'.app()->getLocale()] ,
            'answer' => $this['content_'.app()->getLocale()],
        ];
    }
}
