<?php

namespace App\Http\Resources\Exam;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamAnswerResource extends JsonResource
{
    private static $data;
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
            'content' => $this->content,
            'comment' => $this->comment ?? null,
            'is_correct' => $this->when(self::$data['is_ended'], (bool) $this->is_correct),
            'is_chosen' => $this->when(self::$data['is_ended'], (bool) $this->is_chosen),
        ];
    }

    public static function _collection($resource, $data = null)
    {
        self::$data = $data;
        return parent::collection($resource);
    }
}
