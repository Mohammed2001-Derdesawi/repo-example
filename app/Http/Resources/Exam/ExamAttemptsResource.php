<?php

namespace App\Http\Resources\Exam;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

class ExamAttemptsResource extends JsonResource
{



    public function toArray($request)
    {

//        $attempts = $this->exams()->where('user_id' , auth('api')->id());

        return [
            'id' => $this->id,
            'course_id' => $this->course_id,
            'title' => __('db.exam_type.'.$this->type) . ': ' . $this->{strtolower($this->type)}?->t('name'),
            'duration' => $this->duration ,
            'questions_count' => $this->questions?->count() ?? 0,
            'attempts_count' => $this->exams()->where('user_id' , auth('api')->id())->count(),
            'is_restartable' => Gate::allows('start-exam', $this),
            'is_not_ended_attempt' => $this->exams()->where('user_id' , auth('api')->id())->where('is_ended', false)->first()?->id ?? null,
            'attempts' => ExamUserResource::collection($this->exams()->where('user_id' , auth('api')->id())->orderBy('created_at')->get())
        ];
    }
}
