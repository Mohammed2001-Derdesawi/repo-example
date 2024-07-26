<?php

namespace App\Http\Resources\Course\Mobile;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

class CourseExamResource extends JsonResource
{

    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'image' => $this->course->image,
            'field' => $this->course->category->field->t('name'),
            'name' => __('messages.result_of_exam', ['type' => __('db.exam_type.'.$this->type), 'name' => $this->{strtolower($this->exam?->type)}->t('name')]),
            'duration' => $this->duration,
            'questions' => $this->questions->count(),
            'is_performed' => auth('api')?->user()->exams->contains('exam_id', $this->id) ?? false,
            'is_restartable' => Gate::allows('start-exam', $this),



        ];
    }
}
