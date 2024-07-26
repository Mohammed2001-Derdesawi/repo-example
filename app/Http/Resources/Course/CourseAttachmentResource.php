<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseAttachmentResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'name' => $this->t('name'),
            'file' => $this->file ?? null,
        ];
    }
}
