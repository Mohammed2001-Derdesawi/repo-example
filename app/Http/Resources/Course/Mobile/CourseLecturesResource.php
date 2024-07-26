<?php

namespace App\Http\Resources\Course\Mobile;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseLecturesResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->t('name'),
            'description' => $this->t('description'),
            'is_free' => $this->is_free,
            'duration' => $this->duration,
            'details' => $this->is_free ? new LectureResource($this) : null,
        ];
    }
}
