<?php

namespace App\Http\Resources\Structure\Home;

use Illuminate\Http\Resources\Json\JsonResource;

class SectionSixAccountsResource extends JsonResource
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
            'icon' => $this->icon,
            'account' => $this->account,
        ];
    }
}
