<?php

namespace App\Http\Resources\Course;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

class CourseUnsubscribedExamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $examUser = auth('api')->user()?->exams()?->where('exam_id', $this->id)->orderByDesc('exam_user.id')->withPivot('id', 'created_at', 'is_ended', 'exam_id');

        return [
            'id' => $this->is_free ? $this->id : null,
            'is_free' => $this->is_free,
            'name' => __('db.exam_type.'.$this->type) . ': ' . $this->{strtolower($this->type)}->t('name'),
            'title' => __('db.exam_type.'.$this->type) . ': ' . $this->{strtolower($this->type)}->t('name'),
            'duration' => $this->duration,
            'questions_count' => $this->questions->count(),
            'is_restartable' => $this->is_free && ( !auth('api')->user() || Gate::allows('start-exam', $this)),
            'exam_user' => auth('api')->user() && $examUser->exists()
                ? [
                    'id' => $examUser?->first()->pivot->id,
                    'date' => Carbon::parse($examUser->first()->pivot->created_at)->translatedFormat('d F Y  h:iA'),
                    'is_ended' => (bool) $examUser->first()->pivot->is_ended,
                ]
                : null,        ];
    }
}
