<?php

namespace App\Http\Resources\Course;

use Alkoumi\LaravelHijriDate\Hijri;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ExamIntroductoryVideoEmbedResource extends JsonResource
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
            'solution_video_platform' => $this->solution_video_platform,
            'solution_video_link' => $this->solution_video_link ,
        ];
    }
}
