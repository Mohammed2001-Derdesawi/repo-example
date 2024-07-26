<?php

namespace App\Http\Services\Api\Payment;

use App\Models\Cart;
use App\Models\CertificateUser;

trait Payable
{
    protected array $payable = [
        'cart' => [
            'type' => Cart::class,
            'amount' => 'total_amount',
            'repository' => 'cartRepository',
            'columns' => ['id', 'coupon_id'],
        ],
        'certificate_user' => [
            'type' => CertificateUser::class, // represents a certificate
            'amount' => 'amount',
            'repository' => 'certificateUserRepository',
            'columns' => ['id', 'course_id'],
        ]
    ];
}
