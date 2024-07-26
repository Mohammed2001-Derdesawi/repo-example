<?php

namespace App\Http\Resources\Course;

use App\Repository\ExamUserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

class CourseExamsByTypeResource extends JsonResource
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
            'field' => $this->course?->category?->field?->t('name'),
            'id' => $this->id,
            'title' => __('db.exam_type.'.$this->type) . ': ' . $this->{strtolower($this->type)}->t('name'),
            'image' => $this->course?->image !== null ? url($this->course?->image) : null,
            'questions_count' => $this->questions?->count(),
            'duration' => $this->duration,
            'is_restartable' => Gate::allows('start-exam', $this),
            'solution_video_platform' => $this->solution_video_platform,
            'solution_video_link' => $this->solution_video_link,
            'exam_user' => $examUser->exists()
                ? [
                    'id' => $examUser?->first()->pivot->id,
                    'date' => Carbon::parse($examUser->first()->pivot->created_at)->translatedFormat('d F Y  h:iA'),
                    'is_ended' => (bool) $examUser->first()->pivot->is_ended,
                ]
                : null,
        ];
    }
}
