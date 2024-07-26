<?php

namespace App\Http\Resources\Exam;

use App\Repository\AnswerUserRepositoryInterface;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamResultResource extends JsonResource
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

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'title' => __('messages.result_of_exam', ['type' => __('db.exam_type.'.$this->exam?->type), 'name' => $this->exam?->{strtolower($this->exam?->type)}->t('name')]),
            'total_result_percentage' => $this->answers->count() > 0 ? (float) number_format(($this->answerUserRepository->isCorrectCount($this->id) / $this->answers->count()) * 100, 2) . '%' : 0,
            'correct_answers' => $this->answerUserRepository->isCorrectCount($this->id) ,
            'wrong_answers' => $this->answers->count() - $this->answerUserRepository->isCorrectCount($this->id),
            'type_name' => __('db.exam_type_single.'.$this->exam->questions_type),
            'types' => $this->{$this->exam->questions_type.'s'} !== null ? ExamResultTypesResource::_collection($this->{$this->exam->questions_type.'s'}, $this->answerUserRepository, $this->id, $this->exam->questions_type) : [],
        ];
    }
}
