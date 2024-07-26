<?php

namespace App\Http\Resources\Cart;

use App\Models\Course;
use Illuminate\Http\Resources\Json\JsonResource;

class CartContentResource extends JsonResource
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
            'name' => __('dashboard.'.$this->cartable_type). ': ' . $this->cartable->t('name') . ($this->option !== null ? ' (' . __('dashboard.'.$this->option) . ')' : ''),
            'image' => $this->cartable->image,
            'quantity' => $this->quantity ?? 1,
//            'amount' => $this->amount,
            'amount' => $this->total,
            'app_amount' => $this->app_amount,
            'discount' => $this->discount_amount,
            'final' => $this->final_amount,
            'field' => ($this->cartable->category !== null ? $this->cartable?->category?->field?->t('name') : $this->cartable?->course?->category?->field?->t('name')) ?? null,
        ];
    }
}
