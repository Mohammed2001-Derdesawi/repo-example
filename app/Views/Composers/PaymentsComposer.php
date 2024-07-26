<?php

namespace App\Views\Composers;

use App\Repository\PaymentRepositoryInterface;
use Illuminate\View\View;

class PaymentsComposer {

    private PaymentRepositoryInterface $paymentRepository;

    public function __construct(
        PaymentRepositoryInterface $paymentRepository,
    ) {
        $this->paymentRepository = $paymentRepository;
    }

    public function compose(View $view) {
        $paymentsCount = $this->paymentRepository->notActivatedCount();
        $view->with('paymentsCount', $paymentsCount);
   }
}
