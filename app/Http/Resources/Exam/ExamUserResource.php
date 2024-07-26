<?php

namespace App\Http\Resources\Exam;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

class ExamUserResource extends JsonResource
{



    public function toArray($request)
    {




        $examUser = auth('api')->user()?->exams()?->where('exam_id', $this->exam_id)->orderByDesc('exam_user.id')->withPivot('id', 'created_at', 'is_ended', 'exam_id');

        return [
//            'field' => $this->exam?->course?->category?->field?->t('name'),
            'id' => $this->id,
//            'title' => __('db.exam_type.'.$this->exam?->type) . ': ' . $this->exam?->{strtolower($this->exam?->type)}?->t('name'),
//            'image' => $this->exam?->course?->image !== null ? url($this->exam?->course?->image) : null,
            'date' => Carbon::parse($this->created_at)->translatedFormat('d F Y  h:iA'),
//            'questions_count' => $this->exam?->questions?->count(),
//            'duration' => $this->exam?->duration ,
            'is_ended' => (bool) $this->is_ended,
//            'is_restartable' => Gate::allows('start-exam', $this->exam),
//            'exam_user' => $examUser->exists()
//                ? [
//                    'id' => $examUser->first()->pivot->id,
//                    'date' => Carbon::parse($examUser->first()->pivot->created_at)->translatedFormat('d F Y  h:iA'),
//                    'is_ended' => (bool) $examUser->first()->pivot->is_ended,
//                ]
//                : null,
        ];
    }
}
