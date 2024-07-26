<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseInquireResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'question' => $this->question,
            'answer' => $this->answer,
            'attachment' => $this->attachment,
            'answer_voice' => $this->answer_voice ? url($this->answer_voice) : null,
            'is_public' => $this->is_public,
        ];
    }
}
