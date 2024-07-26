<?php

namespace App\Http\Resources\Course\Mobile;

use App\Http\Resources\Course\CourseTeacherResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseAboutResource extends JsonResource
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
            'description' => $this->t('description'),
            'teachers' => CourseTeacherResource::collection($this->teachers),
        ];
    }
}
