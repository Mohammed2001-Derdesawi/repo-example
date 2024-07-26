<?php

namespace App\Http\Resources\Course\Web;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

class CourseSubscribedExamResource extends JsonResource
{

    public function toArray($request)
    {
        $examUser = auth('api')->user()?->exams()?->where('exam_id', $this->id)->orderByDesc('exam_user.id')->withPivot('id', 'created_at', 'is_ended', 'exam_id');

        $check = false;
        if ($this->is_free)
            $check = true;
        elseif (auth('api')->user() && auth('api')->user()->courses()?->where('course_id', $this?->course_id)?->exists())
            $check = true;
        return [
            'id' => $check ? $this->id : null,
            'is_free' => $this->is_free,
            'name' => __('db.exam_type.' . $this->type) . ': ' . $this->{strtolower($this->type)}->t('name'),
            'duration' => $this->duration,
            'questions_count' => $this->questions->count(),
            'field' => $this->course?->category?->field?->t('name'),
            'title' => __('db.exam_type.' . $this->type) . ': ' . $this->{strtolower($this->type)}->t('name'),
            'image' => $this->course?->image !== null ? url($this->course?->image) : null,
            'is_restartable' => Gate::allows('start-exam', $this),
            'solution_video_platform' => $this->solution_video_platform,
            'solution_video_link' => $this->solution_video_link,
            'exam_user' => $examUser->exists()
                ? [
                    'id' => $examUser?->first()->pivot->id,
                    'date' => Carbon::parse($examUser->first()->pivot->created_at)->translatedFormat('d F Y  h:iA'),
                    'is_ended' => (bool)$examUser->first()->pivot->is_ended,
                ]
                : null,
        ];
    }
}
