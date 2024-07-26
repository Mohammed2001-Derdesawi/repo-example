<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseLectureEmbedResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'name' => $this->t('name'),
            'link_platform' => $this->link_platform,
            'video_link' => $this->standard?->course?->isSubscribed() || $this->is_free ? $this->record_link : null,
        ];
    }
}
