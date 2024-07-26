<?php

namespace App\Http\Controllers\Dashboard\Home;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Repository\CourseRepositoryInterface;
use App\Repository\ExamRepositoryInterface;
use App\Repository\ManagerRepositoryInterface;
use App\Repository\PaymentRepositoryInterface;
use App\Repository\PrintRequestRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    private CourseRepositoryInterface $courseRepository;
    private UserRepositoryInterface $userRepository;
    private ManagerRepositoryInterface $managerRepository;
    private ExamRepositoryInterface $examRepository;
    private PaymentRepositoryInterface $paymentRepository;
    private PrintRequestRepositoryInterface $printRequestRepository;

    public function __construct(
        CourseRepositoryInterface $courseRepository,
        UserRepositoryInterface $userRepository,
        ManagerRepositoryInterface $managerRepository,
        ExamRepositoryInterface $examRepository,
        PaymentRepositoryInterface $paymentRepository,
        PrintRequestRepositoryInterface $printRequestRepository,
    ){
        $this->courseRepository = $courseRepository;
        $this->userRepository = $userRepository;
        $this->managerRepository = $managerRepository;
        $this->examRepository = $examRepository;
        $this->paymentRepository = $paymentRepository;
        $this->printRequestRepository = $printRequestRepository;
    }

    public function index() {
        $coursesCount = $this->courseRepository->coursesCount();
        $courses = $this->courseRepository->whereHasSubscribes();
        $studentsCount = $this->userRepository->getCount();
        $teachersCount = $this->managerRepository->getTeachersCount();
        $paymentsCount = $this->paymentRepository->getPaymentsCount();
        $waitingPrintRequests = $this->printRequestRepository->getPendingCount();
        $examsCount = $this->examRepository->getCount();
        return view('dashboard.site.home.index' , [
            'coursesCount' => $coursesCount,
            'studentsCount' => $studentsCount,
            'teachersCount' => $teachersCount,
            'examsCount' => $examsCount,
            'paymentsCount' => $paymentsCount,
            'waitingPrintOrdersCount' => $waitingPrintRequests,
            'courses' => $courses
        ]);
    }

}
