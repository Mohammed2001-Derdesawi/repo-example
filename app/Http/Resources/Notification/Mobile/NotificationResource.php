<?php

namespace App\Http\Resources\Notification\Mobile;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{

    public function toArray($request)
    {
        return [
                    'id' => $this->id,
                    'user' => $this->user->name,
                    'title' => $this->title,
                    'body' => $this->body,
                    'type' => $this->type??'',
                    'content' => $this->content??'',
                    'subscribe' => $this->is_subscribe??'',
                ];
    }
}
