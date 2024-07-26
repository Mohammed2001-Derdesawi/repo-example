<?php

namespace App\Http\Resources\Lecture;

use Illuminate\Http\Resources\Json\JsonResource;

class PinResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'lecture_id' => $this->lecture_id,
            'name' => $this->t('name'),
            'time' => $this->time
        ];
    }
}
