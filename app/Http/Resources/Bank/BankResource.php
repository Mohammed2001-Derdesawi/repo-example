<?php

namespace App\Http\Resources\Bank;

use Illuminate\Http\Resources\Json\JsonResource;

class BankResource extends JsonResource
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
            'name' => $this->t('name'),
            'iban_number' => $this->iban_number,
            'account_number' => $this->account_number,
            'account_name' => $this->t('account_name')
        ];
    }
}
