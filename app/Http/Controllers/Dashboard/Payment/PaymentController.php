<?php

namespace App\Http\Controllers\Dashboard\Payment;

use App\Http\Controllers\Controller;
use App\Http\Services\Dashboard\Payment\PaymentService;
use App\Http\Services\Mutual\ExportService;
use App\Models\Course;
use App\Models\CourseBook;
use App\Repository\CartRepositoryInterface;
use App\Repository\CouponRepositoryInterface;
use App\Repository\CourseRepositoryInterface;
use App\Repository\PaymentRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected PaymentService $payment;
    private PaymentRepositoryInterface $paymentRepository;
    private ExportService $export;

    protected CourseRepositoryInterface $courseRepository;

    public function __construct(
        PaymentService $paymentService,
        PaymentRepositoryInterface $paymentRepository,
        ExportService $export,
        CourseRepositoryInterface $courseRepository
    ) {
        Log::info('extension ::: ');
        $this->middleware('auth');
        $this->middleware('permission:payments-read')->only(['index', 'show']);
        $this->middleware('permission:payments-update')->only('confirm');
        $this->payment = $paymentService;
        $this->paymentRepository = $paymentRepository;
        $this->export = $export;
        $this->courseRepository = $courseRepository;
    }

    public function index()
    {
        $payments = $this->paymentRepository->getOrderedPayments()->paginate(25);
        $courses = $this->courseRepository->selectAll(['id', 'name_ar', 'name_en']);

        return view('dashboard.site.payments.index', ['payments' => $payments, 'courses' => $courses]);
    }
    public function bankTransfersIndex()
    {
        $payments = $this->paymentRepository->getBankTransfers()->paginate(25);
        return view('dashboard.site.payments.bankTransfers', ['payments' => $payments]);
    }


    public function show($id)
    {
        $payment = $this->paymentRepository->getById($id);
        return view('dashboard.site.payments.show', ['payment' => $payment]);
    }

    public function confirm(Request $request, $id)
    {
        return $this->payment->confirm($id);
    }

    public function decline(Request $request, $id)
    {
        return $this->payment->decline($id);
    }

    public function confirmCourse(Request $request)
    {
        $payment = $this->paymentRepository->getById($request['payment_id'], relations: ['payable']);
        return $this->payment->confirmCourse($payment, $request['course_id'], $request['user_id']);
    }

    public function confirmBook(Request $request)
    {
        $payment = $this->paymentRepository->getById($request['payment_id'], relations: ['payable']);
        return $this->payment->confirmBook($payment, $request['book_id'], $request['user_id'], $request['type']);
    }

    public function exportBank(string $type)
    {
        $coupons = $this->paymentRepository->getBankTransfers()->get();
        $data = [
            'payments' => $coupons
        ];

        return $this->export->handle('payments', 'dashboard.site.payments.export', $data, 'bank_transfers', $type);
    }

    public function exportPayments(string $type)
    {
        $payments = $this->paymentRepository->getOrderedPayments()->get();
        $data = [
            'payments' => $payments,
        ];
        $view = $this->getExportView();
        if (request()->course_id) {
            $data['course'] = $this->courseRepository->getById(request()->course_id, columns: ['id', 'price', 'name_ar', 'name_en']);
        }
        return $this->export->handle('payments', $view, $data, 'payments', $type);
    }

    private function getExportView()
    {
        $view = 'dashboard.site.payments.export';
        if (request()->course_id)
            $view = 'dashboard.site.payments.export_course';
        elseif (request('type') == "CERTIFICATE")
            $view = 'dashboard.site.payments.export_certificate';
        return $view;
    }
}
