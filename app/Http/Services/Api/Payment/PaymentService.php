<?php

namespace App\Http\Services\Api\Payment;

use App\Http\Requests\Api\Payment\PaymentRequest;
use App\Http\Requests\Payment\PaymentCallbackRequest;
use App\Http\Services\Api\WhatsApp\WhatsAppServiceInterface;
use App\Http\Services\Mutual\FileManagerService;
use App\Http\Traits\Responser;
use App\Models\Course;
use App\Models\CourseBook;
use App\Repository\BookUserRepositoryInterface;
use App\Repository\CartRepositoryInterface;
use App\Repository\CertificateUserRepositoryInterface;
use App\Repository\CourseRepositoryInterface;
use App\Repository\CourseUserRepositoryInterface;
use App\Repository\PaymentRepositoryInterface;
use App\Repository\PrintRequestRepositoryInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

abstract class PaymentService
{
    use Responser, Payable;

    protected PaymentRepositoryInterface $paymentRepository;
    protected CartRepositoryInterface $cartRepository;
    protected CourseRepositoryInterface $courseRepository;
    protected CourseUserRepositoryInterface $courseUserRepository;
    protected BookUserRepositoryInterface $bookUserRepository;
    protected PrintRequestRepositoryInterface $printRequestRepository;
    protected CertificateUserRepositoryInterface $certificateUserRepository;
    protected PaymentAssignService $assign;
    protected FileManagerService $fileManager;

    public function __construct(
        PaymentRepositoryInterface         $paymentRepository,
        CartRepositoryInterface            $cartRepository,
        CourseRepositoryInterface          $courseRepository,
        CourseUserRepositoryInterface      $courseUserRepository,
        BookUserRepositoryInterface        $bookUserRepository,
        PrintRequestRepositoryInterface    $printRequestRepository,
        CertificateUserRepositoryInterface $certificateUserRepository,
        PaymentAssignService               $assignService,
        FileManagerService                 $fileManagerService,
    )
    {
        $this->paymentRepository = $paymentRepository;
        $this->cartRepository = $cartRepository;
        $this->courseRepository = $courseRepository;
        $this->courseUserRepository = $courseUserRepository;
        $this->bookUserRepository = $bookUserRepository;
        $this->printRequestRepository = $printRequestRepository;
        $this->certificateUserRepository = $certificateUserRepository;
        $this->assign = $assignService;
        $this->fileManager = $fileManagerService;
    }

    public function ePaymentCallback(PaymentCallbackRequest $request)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('TAP_PAYMENT_SK'),
            'accept' => 'application/json',
        ])->get("https://api.tap.company/v2/charges/{$request->tap_id}")->json();

        Log::info('payment::: ' . print_r($response, true));

        if (isset($response['metadata'])) {
            $payment = $this->paymentRepository->getById($response['metadata']['udf3']);

            if (isset($response['status']) && $response['status'] == 'CAPTURED') {

                if (!$payment->is_confirmed) {
                    if ($payment->payable !== null) {
                        $this->assign->handle($payment, $response['metadata']['udf4'], true);
                    }

                    $this->paymentRepository->update($payment->id, ['is_confirmed' => true, 'is_declined' => false]);
                }

                return $this->responseSuccess(message: __('messages.Payment completed successfully'));
            } else {
                $this->paymentRepository->update($payment->id, ['is_declined' => true, 'is_confirmed' => false]);
                if (isset($response['response'])) {
                    return $this->responseFail(message: __('messages.Payment failed because', ['cause' => $response['response']['message']]));
                } else {
                    return $this->responseFail(message: __('messages.An error occurred while processing the payment'));
                }
            }
        } else {
            return $this->responseFail(message: __('messages.An error occurred while processing the payment'));
        }
    }

    public function ePaymentWebhook(Request $request)
    {
        Log::info('webhook started');
        $payment = $this->paymentRepository->getById($request->metadata['udf3']);

        if ($request->status == 'CAPTURED') {

            $this->assign->handle($payment, $request->metadata['udf4'], true);

            $this->paymentRepository->update($payment->id, ['is_confirmed' => true, 'is_declined' => false]);

            Log::info('webhook done');
            return true;
        } else {
            $this->paymentRepository->update($payment->id, ['is_declined' => true, 'is_confirmed' => false]);
            Log::info('webhook canceled');
            return false;
        }
    }

    public function tamaraPayment($payment, $certificate_user_id = null, $instalments = 3)
    {
        $items = [];
        if ($payment->payable_type == $this->payable['cart']['type']) {
            foreach ($payment->payable->items as $item) {
                if ($item->cartable_type == Course::class) {
                    $items[] = [
                        'name' => __('dashboard.course') . ': ' . $item->cartable->t('name'),
                        'quantity' => 1,
                        'reference_id' => $item->id,
                        'type' => 'Digital',
                        'sku' => 'SA-12437',
                        'total_amount' => [
                            'amount' => $item->cartable->price,
                            'currency' => 'SAR'
                        ]
                    ];
                } elseif ($item->cartable_type == CourseBook::class) {
                    if ($item->option == 'PDF') {
                        $items[] = [
                            'name' => __('dashboard.courseBook') . ': ' . $item->cartable->t('name'),
                            'quantity' => 1,
                            'reference_id' => $item->id,
                            'type' => 'Digital',
                            'sku' => 'SA-12437',
                            'total_amount' => [
                                'amount' => $item->cartable->price,
                                'currency' => 'SAR'
                            ]
                        ];
                    } elseif ($item->option == 'PRINT') {
                        $items[] = [
                            'name' => __('dashboard.printRequest') . ': ' . $item->cartable->t('name'),
                            'quantity' => $item->quantity,
                            'reference_id' => $item->id,
                            'type' => 'Physical',
                            'sku' => 'SA-12437',
                            'total_amount' => [
                                'amount' => $item->cartable->price,
                                'currency' => 'SAR'
                            ]
                        ];
                    }
                }
            }
        } elseif ($payment->payable_type == $this->payable['certificate_user']['type']) {
            $certificate = $this->certificateUserRepository->getById($certificate_user_id);

            $items[] = [
                'name' => __('dashboard.Certificate of Course') . ': ' . $certificate->course->t('name'),
                'quantity' => 1,
                'reference_id' => $certificate->id,
                'type' => 'Physical',
                'sku' => 'SA-12437',
                'total_amount' => [
                    'amount' => $certificate->course->certificate_price,
                    'currency' => 'SAR'
                ]
            ];
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . env('TAMARA_PAYMENT_KEY'),
        ])->post('https://api.tamara.co/checkout', [
            'total_amount' => [
                'amount' => $payment->amount,
                'currency' => 'SAR'
            ],
            'shipping_amount' => [
                'amount' => 0,
                'currency' => 'SAR'
            ],
            'tax_amount' => [
                'amount' => 0,
                'currency' => 'SAR'
            ],
            'order_reference_id' => $payment->id,
            'order_number' => $certificate_user_id ?? 'N/A',
            'items' => $items,
            'consumer' => [
                'email' => auth('api')->user()->email,
                'first_name' => auth('api')->user()->name,
                'last_name' => ' ',
                'phone_number' => auth('api')->user()->phone
            ],
            'country_code' => 'SA',
            'description' => 'test',
            'merchant_url' => [
                'cancel' => 'test',
                'failure' => 'test',
                'success' => 'test',
                'notification' => route('w.tamara.payment.notification')
            ],
            'payment_type' => 'PAY_BY_INSTALMENTS',
            'instalments' => (integer) $instalments,
            'shipping_address' => [
                'first_name' => auth('api')->user()->name,
                'last_name' => '(Student)',
                'line1' => 'N/A',
                'city' => 'N/A',
                'country_code' => 'SA'
            ],
        ])->json();
        if (isset($response['checkout_url'])) {

            DB::commit();

            return $this->responseSuccess(data: ['redirect_url' => $response['checkout_url']]);

        } else {
            $this->paymentRepository->delete($payment->id);

            DB::commit();
            return $this->responseFail(data: __('messages.An error occurred while processing the payment'));
        }
    }

    public function tamaraNotification(Request $request)
    {
        if ($request->order_id !== null && $request->order_status == 'approved') {

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . env('TAMARA_PAYMENT_KEY'),
            ])->post('https://api.tamara.co/orders/' . $request->order_id . '/authorise')->json();

            $tamaraOrder = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . env('TAMARA_PAYMENT_KEY'),
            ])->get('https://api.tamara.co/orders/' . $request->order_id)->json();

            $payment = $this->paymentRepository->getById($tamaraOrder['order_reference_id']);

            Log::info('tamara notification: ' . print_r($tamaraOrder, true));

            if ($response['order_id'] !== null && ($response['status'] == 'authorised' || $response['status'] == 'fully_captured')) {
                $certificate_user_id = $request->order_number !== 'N/A' ? $request->order_number : null;
                $this->assign->handle($payment, $certificate_user_id, true);

                $this->paymentRepository->update($payment->id, ['is_confirmed' => true, 'is_declined' => false]);

                return true;
            } else {
                $this->paymentRepository->update($payment->id, ['is_declined' => true, 'is_confirmed' => false]);
                return false;
            }
        } else {
            return false;
        }

    }
}
