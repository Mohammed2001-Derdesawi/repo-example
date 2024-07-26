<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseCertificateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->when($this->user_progress == 100, [
            'id' => $this->id,
            'image' => $this->image,
            'name' => $this->t('name'),
            'price' => $this->certificate_price ?? null
        ]);
    }
}
