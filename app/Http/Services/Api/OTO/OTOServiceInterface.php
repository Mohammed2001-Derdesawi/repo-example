<?php

namespace App\Http\Services\Api\OTO;

interface OTOServiceInterface {
    public function createOrder($paymentItem,$payment);
    public function cancel($otoId);

}
