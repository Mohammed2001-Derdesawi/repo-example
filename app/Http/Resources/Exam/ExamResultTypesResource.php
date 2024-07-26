<?php

namespace App\Http\Resources\Exam;

use App\Repository\AnswerUserRepositoryInterface;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamResultTypesResource extends JsonResource
{
    private static AnswerUserRepositoryInterface $answerUserRepository;
    private static $examUserId;
    private static $typeRelationName;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $answersCountByType = self::$answerUserRepository->countByType(static::$examUserId, static::$typeRelationName, $this->id, false);
        $correctAnswersCountByType = self::$answerUserRepository->countByType(static::$examUserId, static::$typeRelationName, $this->id);
        $questionsByType = self::$answerUserRepository->getQuestionsByType(static::$examUserId, static::$typeRelationName, $this->id, false);
        return [
            'name' => $this->t('name'),
            'questions_count' => $answersCountByType,
            'result_percentage' => number_format((float) ($correctAnswersCountByType / $answersCountByType) * 100, 2) . '%',
            'questions' => ExamQuestionResource::collection($questionsByType),
        ];
    }

    public static function _collection($resource, AnswerUserRepositoryInterface $answerUserRepository, $examUserId, $typeRelationName)
    {
        self::$answerUserRepository = $answerUserRepository;
        self::$examUserId = $examUserId;
        self::$typeRelationName = $typeRelationName;
        return parent::collection($resource);
    }
}
