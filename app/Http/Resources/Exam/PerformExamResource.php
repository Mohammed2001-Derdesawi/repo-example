<?php

namespace App\Http\Resources\Exam;

use App\Repository\AnswerUserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PerformExamResource extends JsonResource
{
    private AnswerUserRepositoryInterface $answerUserRepository;

    public function __construct(
        $resource,
        AnswerUserRepositoryInterface $answerUserRepository,
    )
    {
        parent::__construct($resource);
        $this->answerUserRepository = $answerUserRepository;
    }

    public function toArray($request)
    {
        return [
            'course_id' => $this->exam?->course_id,
            'exam_id' => $this->exam?->id,
            'title' => __('db.exam_type.'.$this->exam?->type) . ': ' . $this->exam?->{strtolower($this->exam?->type)}->t('name'),
            'questions_count' => $this->exam?->questions?->count(),
            'duration' => $this->exam?->duration,
            'ends_at' => Carbon::now()->isBefore($this->ends_at) ? Carbon::parse($this->ends_at)->diffInSeconds(Carbon::now()) : 0,
            'is_ended' => (bool) $this->is_ended,
            'correct_answers' => $this->when($this->is_ended, $this->answerUserRepository->isCorrectCount($this->id) ),
            'wrong_answers' => $this->when($this->is_ended, $this->answers->count() - $this->answerUserRepository->isCorrectCount($this->id)),
            'show_result_details' => $this->is_ended && $this->exam?->type !== 'LECTURE',
            'solution_video_platform' => $this->exam->solution_video_platform ?? null,
            'solution_video_link' => $this->exam->solution_video_link ?? null,
            'questions' => ExamQuestionResource::collection($this->orderAnswers),
        ];
    }
}
