<?php

namespace App\Http\Services\Api\Payment;

use Throwable;
use App\Models\Course;
use App\Models\CourseBook;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Http\Services\Api\OTO\OTOService;
use App\Repository\CartRepositoryInterface;
use App\Repository\CourseRepositoryInterface;
use App\Repository\BookUserRepositoryInterface;
use App\Repository\CourseUserRepositoryInterface;
use App\Http\Services\Api\OTO\OTOServiceInterface;
use App\Repository\PaymentItemRepositoryInterface;
use App\Repository\PrintRequestRepositoryInterface;
use App\Repository\CertificateUserRepositoryInterface;
use App\Http\Services\Api\WhatsApp\WhatsAppServiceInterface;

class PaymentConfirmationService
{
    use Payable;

    private CartRepositoryInterface $cartRepository;
    private CourseRepositoryInterface $courseRepository;
    private CourseUserRepositoryInterface $courseUserRepository;
    private BookUserRepositoryInterface $bookUserRepository;
    private PrintRequestRepositoryInterface $printRequestRepository;
    private CertificateUserRepositoryInterface $certificateUserRepository;
    private PaymentItemRepositoryInterface $paymentItemRepository;
    private WhatsAppServiceInterface $whatsAppService;
    private OTOServiceInterface $otoService;

    public function __construct(
        CartRepositoryInterface            $cartRepository,
        CourseRepositoryInterface          $courseRepository,
        CourseUserRepositoryInterface      $courseUserRepository,
        BookUserRepositoryInterface        $bookUserRepository,
        PrintRequestRepositoryInterface    $printRequestRepository,
        CertificateUserRepositoryInterface $certificateUserRepository,
        PaymentItemRepositoryInterface     $paymentItemRepository,
        WhatsAppServiceInterface     $whatsAppService,
        OTOServiceInterface $otoServiceInterface
    )
    {
        $this->cartRepository = $cartRepository;
        $this->courseRepository = $courseRepository;
        $this->courseUserRepository = $courseUserRepository;
        $this->bookUserRepository = $bookUserRepository;
        $this->printRequestRepository = $printRequestRepository;
        $this->certificateUserRepository = $certificateUserRepository;
        $this->paymentItemRepository = $paymentItemRepository;
        $this->whatsAppService = $whatsAppService;
        $this->otoService = $otoServiceInterface;
    }

    public function course($payment, $item)
    {
        $oldcourse = Course::find($item->cartable_id);
        $end_subscribe = null;
        if ($oldcourse->dayNumbers) {
            $enddate = Carbon::parse(now())->addDays($oldcourse->dayNumbers);
            $end_subscribe = $enddate->toDateString();
        }
        $this->courseUserRepository->create([
            'user_id' => $payment->user_id,
            'course_id' => $item->cartable_id,
            'payment_id' => $payment->id,
            'is_active' => true,
            'end_subscribe' => $end_subscribe,
        ]);
        $item->delete();

        if($oldcourse->send_auto_whatsapp_message)
        $this->whatsAppService->sendMessage([
            'message'=>$oldcourse->whatsapp_message,
            'image'=>$oldcourse->whatsapp_image_url,
            'to'=>$payment->user?->phone,
        ]);
    }

    public function book($payment, $item)
    {
        $otoId=null;
       try{
        DB::beginTransaction();
        if ($item->option == 'PDF') {
            $this->bookUserRepository->create([
                'user_id' => $payment->user_id,
                'course_book_id' => $item->cartable_id,
                'payment_id' => $payment->id,
                'is_active' => true,
            ]);
            $paymentItemRepo=$this->paymentItemRepository->create([
                'user_id' => $payment->user_id,
                'name_ar' => __('dashboard.courseBook', locale: 'ar') . ': ' . $item->cartable->name_ar,
                'name_en' => __('dashboard.courseBook', locale: 'en') . ': ' . $item->cartable->name_en,
                'amount' => $item->cartable->price,
                'discounted_amount' => $payment->payable()->withTrashed()->first()?->coupon ? (1 - ($payment->payable()->withTrashed()->first()?->coupon->discount / 100)) * $item->cartable->price : $item->cartable->price,
            ]);
            $item->delete();
            DB::commit();
            return $paymentItemRepo;
        } elseif ($item->option == 'PRINT') {
            $printRequest=$this->printRequestRepository->create([
                'user_id' => $payment->user_id,
                'type' => 'BOOK',
                'course_id' => $item->cartable->course_id,
                'book_id' => $item->cartable_id,
                'quantity' => $item->quantity,
                'payment_id' => $payment->id,
            ]);
             $paymentItem=$this->paymentItemRepository->create([
                'user_id' => $payment->user_id,
                'name_ar' => __('dashboard.printRequest', locale: 'ar') . ': ' . $item->cartable->name_ar,
                'name_en' => __('dashboard.printRequest', locale: 'en') . ': ' . $item->cartable->name_en,
                'quantity' => $item->quantity,
                'amount' => $item->quantity * $item->cartable->price,
                'discounted_amount' => $item->quantity * ($payment->payable()->withTrashed()->first()?->coupon ? (1 - ($payment->payable()->withTrashed()->first()?->coupon->discount / 100)) * $item->cartable->price : $item->cartable->price),
            ]);
            // create shipment Order if success
            $otoId=$this->otoService->createOrder($paymentItem,$payment);
            if($otoId)
            $this->printRequestRepository->update($printRequest->id,['oto_id'=>$otoId]);
            $item->delete();
            DB::commit();
            return $item;
        }
       }catch(Throwable $e){
        Log::error($e->getMessage());
        DB::rollBack();
        if($otoId)
        $this->otoService->cancel($otoId);
        return null;
       }
    }

    public function certificate($payment, $certificate)
    {
        $printRequest = $this->printRequestRepository->create([
            'user_id' => $payment->user_id,
            'type' => 'CERTIFICATE',
            'course_id' => $certificate->course_id,
            'payment_id' => $payment->id,
        ]);
        return $certificate->update([
            'print_request_id' => $printRequest->id,
            'is_active' => true,
        ]);
    }

    public function notify($user, $notifiable_items)
    {
        if (env('APP_ENV') == 'production') {
            Mail::send(
                'emails.items_activated',
                [
                    'user' => $user,
                    'notifiable_items' => $notifiable_items
                ],
                function ($message) use ($user, $notifiable_items) {
                    $message->to($user->email, $user->name);
                    $message->subject('تم تفعيل ' . ($notifiable_items->count() == 1 ? 'كتاب' : $notifiable_items->count() . 'كتب') . ' في حسابك ');
                    $message->from(env('PRODUCTION_NO_REPLY_MAIL'), 'منصة ميم التعليمية');
                }
            );
        }

    }

}
