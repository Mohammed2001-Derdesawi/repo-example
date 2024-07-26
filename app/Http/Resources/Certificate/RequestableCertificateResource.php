<?php

namespace App\Http\Resources\Certificate;

use Illuminate\Http\Resources\Json\JsonResource;

class RequestableCertificateResource extends JsonResource
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
            'image' => $this->image,
            'price' => $this->certificate_price,
        ];
    }
}
