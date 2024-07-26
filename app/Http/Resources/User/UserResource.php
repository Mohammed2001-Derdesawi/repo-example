<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'communication_code' => $this->communication_code,
            'phone' => $this->phone,
            'image' => $this->image,
            'is_verified' => $this->is_verified,
            'cart_length' => $this->cart ? $this->cart->items->count()  : 0,
            'token' => $this->token()
        ];
    }
}
