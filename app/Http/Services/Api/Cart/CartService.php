<?php

namespace App\Http\Services\Api\Cart;

use App\Http\Requests\Api\Cart\AddToCartRequest;
use App\Http\Requests\Api\Cart\ApplyCouponCartRequest;
use App\Http\Requests\Api\Cart\RemoveFromCartRequest;
use App\Http\Resources\Cart\CartResource;
use App\Http\Services\Mutual\GetService;
use App\Http\Traits\Responser;
use App\Models\Course;
use App\Models\CourseBook;
use App\Repository\CartContentRepositoryInterface;
use App\Repository\CartRepositoryInterface;
use App\Repository\CouponRepositoryInterface;
use App\Repository\CourseBookRepositoryInterface;
use App\Repository\CourseRepositoryInterface;
use Illuminate\Support\Facades\Gate;

abstract class CartService
{
    use Responser;

    protected CartRepositoryInterface $cartRepository;
    protected CartContentRepositoryInterface $cartContentRepository;
    protected CourseRepositoryInterface $courseRepository;
    protected CourseBookRepositoryInterface $courseBookRepository;
    protected CouponRepositoryInterface $couponRepository;
    protected GetService $get;
    private array $cartable = [
        'course' => Course::class,
        'courseBook' => CourseBook::class,
    ];

    public function __construct(
        CartRepositoryInterface        $cartRepository,
        CartContentRepositoryInterface $cartContentRepository,
        CourseRepositoryInterface      $courseRepository,
        CourseBookRepositoryInterface  $courseBookRepository,
        CouponRepositoryInterface      $couponRepository,
        GetService                     $getService,
    ) {
        $this->cartRepository = $cartRepository;
        $this->cartContentRepository = $cartContentRepository;
        $this->courseRepository = $courseRepository;
        $this->courseBookRepository = $courseBookRepository;
        $this->couponRepository = $couponRepository;
        $this->get = $getService;
    }

    private function provide()
    {
        return $this->cartRepository->provide();
    }

    public function show()
    {
        return $this->get->handle(resource: CartResource::class, repository: $this->cartRepository, method: 'provide', is_instance: true);
    }

    private function addDecide($data, $cart, $itemId, $itemType, $option)
    {
        if ($cart->items()?->where('cartable_id', $itemId)->where('cartable_type', $itemType)->where('option', $option)->exists()) {
            return $this->responseFail(message: __('messages.Item is already in cart', ['type' => __('dashboard.' . $data['item_type'])]));
        }

        if ($itemType == Course::class && auth('api')->user()->courses()->where('course_id', $itemId)->exists()) {
            return $this->responseFail(message: __('messages.You already have this course', ['type' => __('dashboard.' . $data['item_type'])]));
        }

        if ($itemType == CourseBook::class && auth('api')->user()->books()->where('course_books.id', $itemId)->exists() && $data['option'] == 'PDF') {
            return $this->responseFail(message: __('messages.You already have this course', ['type' => __('dashboard.' . $data['item_type'])]));
        }

        if ($itemType == Course::class && !$this->courseRepository->canBeRegistered($itemId)) {
            return $this->responseFail(message: __('messages.Registration Is Closed For This Course'));
        }

        if (
            $itemType == Course::class &&

            auth('api')->user()->cart()?->withTrashed()->where(function ($query) use ($itemId) {
                $query->whereHas('items', function ($query) use ($itemId) {
                    $query->where('cartable_type', Course::class);
                    $query->where('cartable_id', $itemId);
                });
            })->where(function ($query) {
                $query->whereHas('payment', function ($query) {
                    $query->where('is_confirmed', false);
                    $query->where('is_declined', false);
                });
            })->exists()
        ) {
            return $this->responseFail(message: __('messages.This item is on a previous payment that is being reviewed', ['type' => __('dashboard.' . $data['item_type'])]));
        }

        $item = $this->cartContentRepository->create([
            'cart_id' => $cart['id'],
            'cartable_type' => $this->cartable[$data['item_type']],
            'cartable_id' => $data['item_id'],
            'option' => $data['option'] ?? null,
            'quantity' => isset($data['option']) && $data['option'] == "PRINT" ? $data['quantity'] ?? 1 : 1,
        ]);
        $this->subscribeCoursesAutoDiscount($item);
        return $this->responseSuccess(message: __('messages.Item added to cart successfully', ['type' => __('dashboard.' . $data['item_type'])]));
    }

    public function add(AddToCartRequest $request)
    {
        $cart = $this->provide();
        $data = $request->validated();
        return $this->addDecide($data, $cart, $data['item_id'], $this->cartable[$data['item_type']], $request->option);
    }

    public function remove(RemoveFromCartRequest $request)
    {
        $cart = $this->provide();
        $data = $request->validated();
        if (Gate::allows('remove-from-cart', [$cart, $data['cart_item_id']])) {
            $this->cartContentRepository->forceDelete($data['cart_item_id']);
            return $this->responseSuccess(message: __('messages.Item removed from cart successfully'));
        } else {
            return $this->responseFail(status: 401, message: __('messages.Cannot remove this item from cart'));
        }
    }

    public function applySubscriberCopoun($cart, $coupon, $cartItems)
    {
        $courses = $coupon->subscribe_courses;
        $courses_ids_in_cart = $cartItems->pluck('cartable_id')->toArray();
        $user = auth('api')->user();
        $minCourse=null;

        if(in_array($coupon->basic_subscribe_course_id, $courses) ||
        $this->courseRepository->isUserSubscribed($user->id, $coupon->basic_subscribe_course_id, ['id'])
        || in_array($coupon->basic_subscribe_course_id, $courses_ids_in_cart)){

            $key = array_search($coupon->basic_subscribe_course_id, $courses_ids_in_cart);
            if ($key !== false) {
                unset($courses_ids_in_cart[$key]);
            }
            $minCourse=$this->courseRepository->getCourseFromCoursesByPrice($courses_ids_in_cart);
        }
        if ($minCourse &&  $item = $cartItems->where('cartable_id', $minCourse->id)->where('cartable_type', Course::class)->first()) {
            $cart->update(['coupon_id' => null]);
            $item->update([
                'coupon_id' => $coupon->id
            ]);
            return $this->responseSuccess(message: __('messages.discount applied To') . $item->cartable->t('name'));
        }
        return $this->responseFail(status: 401, message: __('messages.Coupon is not active with this items'));
    }
}
