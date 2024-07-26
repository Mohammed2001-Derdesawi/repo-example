<?php

namespace App\Http\Resources\BookStore;

use Illuminate\Http\Resources\Json\JsonResource;

class BookDetailsResource extends JsonResource
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
            'price' => $this->price,
            'description' => $this->t('description'),
            'image' => $this->course !== null ? $this->course->image : $this->image,
        ];
    }
}
