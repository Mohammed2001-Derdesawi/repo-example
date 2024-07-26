<?php

namespace App\Http\Resources\Cart;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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
            'cart_id' => $this->id,
            'subtotal_amount' => $this->subtotal_amount,
            'discount_amount' => $this->discount_amount,
            'total_amount' => $this->total_amount,
            'hasAddress' => $this->hasAddress(),
            'has_pdf_book' => $this->items()?->where('option', 'PDF')->exists(),
            'items_count' => $this->items_count,
            'items' => CartContentResource::collection($this->items),
            'show_tamara' => !($this->hasBook() == true),
        ];
    }
}
