<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Payment\PaymentRequest;
use App\Http\Requests\Payment\PaymentCallbackRequest;
use App\Http\Services\Api\Payment\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected PaymentService $payment;

    public function __construct(
        PaymentService $paymentService,
    )
    {
        $this->middleware('auth:api');
        $this->payment = $paymentService;
    }

    public function initiate(PaymentRequest $request) {
        return $this->payment->initiate($request);
    }

    public function ePaymentCallback(PaymentCallbackRequest $request)
    {
        return $this->payment->ePaymentCallback($request);
    }

    public function ePaymentWebhook(Request $request)
    {
        return $this->payment->ePaymentWebhook($request);
    }

    public function tamaraNotification(Request $request)
    {
        return $this->payment->tamaraNotification($request);
    }
}
