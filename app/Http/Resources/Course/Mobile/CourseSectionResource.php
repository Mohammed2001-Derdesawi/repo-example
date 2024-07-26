<?php

namespace App\Http\Resources\Course\Mobile;

use App\Http\Resources\Course\CourseTeacherResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseSectionResource extends JsonResource
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
            'has_lectures' => $this->has_lectures,
            'has_books' => $this->has_books,
            'has_exams' => $this->has_exams,
            'has_groups' => $this->has_groups,
            'has_attachments' => $this->has_attachments,
            'has_book_solutions' => $this->has_book_solutions,
            'has_about' => $this->has_about,
        ];
    }
}
