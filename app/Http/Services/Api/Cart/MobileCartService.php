<?php

namespace App\Http\Services\Api\Cart;
use App\Http\Requests\Api\Cart\ApplyCouponCartRequest;
use App\Models\Course;

class MobileCartService extends CartService
{
    private function provide()
    {
        return $this->cartRepository->provide();
    }

    public function applyCoupon(ApplyCouponCartRequest $request)
    {
        $cart = $this->provide();
        $data = $request->validated();
        $coupon = $data['coupon'];
        if ($this->couponRepository->isValid($coupon))
        {
            if ($this->couponRepository->stillUsable($coupon))
                if (!$this->couponRepository->isUsedBefore($coupon))
                {
                    $couponId = $this->couponRepository->first('coupon', $coupon, ['id'])->id;
                    return $this->handleCoupon($couponId , $cart);
                }
                else
                {
                    return $this->responseFail(status: 401, message: __('messages.You have used this coupon before'));
                }
            else
            {
                return $this->responseFail(status: 401, message: __('messages.coupon has used max times'));
            }
        }
        else
        {
            return $this->responseFail(status: 401, message: __('messages.Coupon is invalid'));
        }
    }

    private function handleCoupon($couponId , $cart)
    {
        $coupon = $this->couponRepository->getById($couponId);
        $cart->items()->update(['coupon_id' => null]);
        $cartItems = $cart->items;
        if ($coupon->couponable_type  != null)
        {
            $cart->update(['coupon_id' => null]);
            foreach ($cartItems as $item)
            {
                if ($item->cartable_type == $coupon->couponable_type && $item->cartable_id == $coupon->couponable_id)
                {
                    $item->update([
                                    'coupon_id' => $couponId
                                ]);
                    return $this->responseSuccess(message:  __('messages.discount applied To') . $item->cartable->t('name'));
                }
            }
            return $this->responseFail(status: 401, message: __('messages.Coupon is not active with this items'));
        }
        elseif($coupon->basic_subscribe_course_id && $coupon->subscribe_courses && count($coupon->subscribe_courses)){
            return  $this->applySubscriberCopoun($cart,$coupon,$cartItems);
        }
        else
        {
            $this->cartRepository->update($cart->id, ['coupon_id' => $couponId]);
            return $this->responseSuccess(message: __('messages.Coupon is correct'));
        }
    }

    protected function subscribeCoursesAutoDiscount($item)
    {
        if($item->cartable_type==Course::class){
            $user = auth('api')->user()->load('cart.items');
        $cartItems = $user->cart?->items;
        // check if there is no coupon in cart or in cartItems to apply coupon
        if ($cartItems && count($cartItems) != 0 && !$cartItems->where('coupon_id', '<>', null)->first() && $user->cart->coupon_id == null) {
            $coupon = $this->couponRepository->findByFilters(
                function ($query) use ($item) {
                    $query->whereJsonContains('subscribe_courses',[(string)$item->cartable_id])->active()->where('is_auto_discount',true);
                }
            );
            if($coupon && $this->couponRepository->isValid($coupon->coupon) && $this->couponRepository->stillUsable($coupon->coupon)){
                $this->applySubscriberCopoun($user->cart, $coupon, $cartItems);
            }

        }
        }

    }
}

