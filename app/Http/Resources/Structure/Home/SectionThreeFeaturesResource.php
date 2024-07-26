<?php

namespace App\Http\Resources\Structure\Home;

use Illuminate\Http\Resources\Json\JsonResource;

class SectionThreeFeaturesResource extends JsonResource
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
            'image' => $this->image,
            'title' => $this->title,
            'description' => $this->description ?? '',
        ];
    }
}
