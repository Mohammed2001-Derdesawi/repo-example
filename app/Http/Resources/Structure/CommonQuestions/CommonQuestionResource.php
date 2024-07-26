<?php

namespace App\Http\Resources\Structure\CommonQuestions;

use Illuminate\Http\Resources\Json\JsonResource;

class CommonQuestionResource extends JsonResource
{

    public function toArray($request)
    {
        return [
          'title' => $this->title,
          'questions' => QuestionResource::collection(array_values((array) $this->questions))
        ];
    }
}
