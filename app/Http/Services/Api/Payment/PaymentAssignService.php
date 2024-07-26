<?php

namespace App\Http\Services\Api\Payment;

use App\Models\Course;
use App\Models\CourseBook;
use App\Repository\BookUserRepositoryInterface;
use App\Repository\CartRepositoryInterface;
use App\Repository\CertificateUserRepositoryInterface;
use App\Repository\CourseRepositoryInterface;
use App\Repository\CourseUserRepositoryInterface;
use App\Repository\PaymentItemRepositoryInterface;
use App\Repository\PrintRequestRepositoryInterface;
use Illuminate\Support\Facades\Log;

class PaymentAssignService
{
    use Payable;

    private CartRepositoryInterface $cartRepository;
    private CourseRepositoryInterface $courseRepository;
    private CourseUserRepositoryInterface $courseUserRepository;
    private BookUserRepositoryInterface $bookUserRepository;
    private PrintRequestRepositoryInterface $printRequestRepository;
    private CertificateUserRepositoryInterface $certificateUserRepository;

    protected PaymentConfirmationService $confirm;
    private PaymentItemRepositoryInterface $paymentItemRepository;

    public function __construct(
        CartRepositoryInterface         $cartRepository,
        CourseRepositoryInterface       $courseRepository,
        CourseUserRepositoryInterface   $courseUserRepository,
        BookUserRepositoryInterface     $bookUserRepository,
        PrintRequestRepositoryInterface $printRequestRepository,
        CertificateUserRepositoryInterface $certificateUserRepository,
        PaymentConfirmationService $paymentConfirmationService,
        PaymentItemRepositoryInterface $paymentItemRepository,
    )
    {
        $this->cartRepository = $cartRepository;
        $this->courseRepository = $courseRepository;
        $this->courseUserRepository = $courseUserRepository;
        $this->bookUserRepository = $bookUserRepository;
        $this->printRequestRepository = $printRequestRepository;
        $this->certificateUserRepository = $certificateUserRepository;
        $this->confirm = $paymentConfirmationService;
        $this->paymentItemRepository = $paymentItemRepository;
    }

    public function handle($payment, $certificate_user_id, $confirm = false) {
        if($payment->payable_type == $this->payable['cart']['type']) {
            foreach ($payment->payable->items as $item) {
                if ($item->cartable_type == Course::class) {
                    if ($confirm) {
                        $this->confirm->course($payment, $item);
                    }
                    $this->paymentItemRepository->create([
                        'user_id' => $payment->user_id,
                        'name_ar' => __('dashboard.course', locale: 'ar') . ': ' . $item->cartable->name_ar,
                        'name_en' => __('dashboard.course', locale: 'en') . ': ' . $item->cartable->name_en,
                        'amount' => $item->cartable->price,
                        'discounted_amount' => $payment->payable()->withTrashed()->first()?->coupon ? ( 1 - ($payment->payable()->withTrashed()->first()?->coupon->discount / 100) ) * $item->cartable->price : $item->cartable->price,
                    ]);
                } elseif ($item->cartable_type == CourseBook::class) {
                    if ($confirm) {
                        $this->confirm->book($payment, $item);
                    }
                }
            }

            if ($payment->payable->items()->where('option', 'PDF')->exists() && $payment->is_confirmed) {
                $this->confirm->notify($payment->user, $payment->payable->items()->where('option', 'PDF')->get());
            }

            $payment->payable->delete();
//             $this->cartRepository->delete($payment->payable_id);
        } elseif ($payment->payable_type == $this->payable['certificate_user']['type']) {
            $certificate = $this->certificateUserRepository->getById($certificate_user_id);
            if ($confirm) {
                $this->confirm->certificate($payment, $certificate);
            }
            $this->paymentItemRepository->create([
                'user_id' => $payment->user_id,
                'name_ar' => __('dashboard.Certificate of Course', locale: 'ar') . ': ' . $certificate->course->name_ar,
                'name_en' => __('dashboard.Certificate of Course', locale: 'en') . ': ' . $certificate->course->name_en,
                'amount' => $certificate->course->certificate_price,
                'discounted_amount' => $payment->payable()->withTrashed()->first()?->coupon ? ( 1 - ($payment->payable()->withTrashed()->first()?->coupon->discount / 100) ) * $certificate->course->certificate_price : $certificate->course->certificate_price,
            ]);
        }
    }

}
