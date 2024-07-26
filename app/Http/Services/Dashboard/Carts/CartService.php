<?php

namespace App\Http\Services\Dashboard\Carts;

use App\Repository\CartRepositoryInterface;

class CartService
{
    private CartRepositoryInterface $cartRepository;

    public function __construct(CartRepositoryInterface $cartRepository){
        $this->cartRepository = $cartRepository;
    }
}
