<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseChannelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $telegram_links=$this->telegram_link?explode(',',$this->telegram_link):[];
        $telegram_channel_link=$this->telegram_channel_link?explode(',',$this->telegram_channel_link):[];
        return [
            'whatsapp_link' => $this->whatsapp_link ?? null,
            'telegram_links' =>$telegram_links,
            'telegram_channel_links' =>$telegram_channel_link,
        ];
    }
}
