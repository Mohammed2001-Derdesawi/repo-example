<?php

namespace App\Http\Resources\Field\Web;

use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Course\CourseCommonQuestionsResource;
use Illuminate\Http\Resources\Json\JsonResource;

class FieldResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->t('name'),
            'image' => $this->image,
            'color_code' =>  $this->color_code,
            'show_department' => $this->show_department,
            'categories' => CategoryResource::collection($this->activeCategories),
            'common_questions' => $this->common_questions ? CourseCommonQuestionsResource::collection($this->common_questions) : []
        ];
    }
}
