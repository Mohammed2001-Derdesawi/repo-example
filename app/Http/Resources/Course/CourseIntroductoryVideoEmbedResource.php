<?php

namespace App\Http\Resources\Course;

use Alkoumi\LaravelHijriDate\Hijri;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class CourseIntroductoryVideoEmbedResource extends JsonResource
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
            'name' => $this->t('name'),
            'explanation_video_platform' => $this->explanation_video_platform,
            'explanation_video' => $this->explanation_video ,
        ];
    }
}
