<?php

namespace App\Http\Resources\Exam;

use Illuminate\Http\Resources\Json\JsonResource;

class ExamQuestionResource extends JsonResource
{
    private $_answers;
    private $chosen_answer_id;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->_answers = $this->question()->withTrashed()->first()->answers;
        $this->_answers->map(function ($_answer) {
            $_answer['is_chosen'] = $_answer['id'] == $this->answer_id;
            if ($_answer['id'] == $this->answer_id) {
                $this->chosen_answer_id = $this->answer_id;
            }
        });
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
            'id' => $this->id,
            'content' => $this->question()->withTrashed()->first()->content,
            'is_correct' => $this->when($this->exam?->is_ended, (bool)$this->is_correct),
            'answer' => $this->when($this->exam?->is_ended, $this->chosen_answer_id),
            'answers' => ExamAnswerResource::_collection($this->_answers, $this->exam),
        ];
    }
}
