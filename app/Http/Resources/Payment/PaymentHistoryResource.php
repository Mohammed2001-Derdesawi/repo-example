<?php

namespace App\Http\Resources\Payment;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentHistoryResource extends JsonResource
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
            'quantity' => $this->quantity,
            'amount' => $this->amount,
            'discounted_amount' => $this->discounted_amount,
            'transaction_time' => Carbon::parse($this->created_at)->format('Y-m-d h:iA'),
        ];
    }
}
