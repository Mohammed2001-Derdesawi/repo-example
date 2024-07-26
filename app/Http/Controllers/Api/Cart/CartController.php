<?php

namespace App\Http\Controllers\Api\Cart;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Cart\AddToCartRequest;
use App\Http\Requests\Api\Cart\ApplyCouponCartRequest;
use App\Http\Requests\Api\Cart\RemoveFromCartRequest;
use App\Http\Services\Api\Cart\CartService;

class CartController extends Controller
{
    protected CartService $cart;

    public function __construct(
        CartService $cartService,
    )
    {
        $this->middleware('auth:api');
        $this->cart = $cartService;
    }

    public function show() {
        return $this->cart->show();
    }

    public function add(AddToCartRequest $request) {
        return $this->cart->add($request);
    }

    public function remove(RemoveFromCartRequest $request) {
        return $this->cart->remove($request);
    }

    public function applyCoupon(ApplyCouponCartRequest $request) {
        return $this->cart->applyCoupon($request);
    }

}
