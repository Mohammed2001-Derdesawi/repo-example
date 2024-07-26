<?php

namespace App\Http\Resources\Certificate;

use Illuminate\Http\Resources\Json\JsonResource;

class CertificateResource extends JsonResource
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
            'name' => $this->course->t('name'),
            'image' => $this->course->image,
            'price' => $this->course->certificate_price,
            'status' => __('dashboard.' . $this->printRequest?->status ?? "ORDERED"),
        ];
    }
}
