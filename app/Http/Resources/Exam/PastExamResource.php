<?php

namespace App\Http\Resources\Exam;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

class PastExamResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'field' => $this->course?->category?->field?->t('name'),
            'course' => $this->course?->t('name'),
            'id' => $this->id,
            'title' => __('db.exam_type.'.$this->type) . ': ' . $this->{strtolower($this->type)}?->t('name'),
            'image' => $this->course?->image !== null ? url($this->course?->image) : null,
            'duration' => $this->duration ,
            'questions_count' => $this->questions?->count(),
        ];
    }
}
