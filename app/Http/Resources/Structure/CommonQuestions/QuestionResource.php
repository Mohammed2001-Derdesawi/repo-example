<?php

namespace App\Http\Resources\Structure\CommonQuestions;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{

    public function toArray($request)
    {
        return [
          'question' => $this->question,
          'answer' => $this->answer
        ];
    }
}
