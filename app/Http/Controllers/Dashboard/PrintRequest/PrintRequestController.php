<?php

namespace App\Http\Controllers\Dashboard\PrintRequest;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\PrintRequests\PrintRequestRequest;
use App\Http\Services\Dashboard\PrintRequest\PrintRequestService;
use App\Http\Services\Mutual\ExportService;
use App\Repository\CourseRepositoryInterface;
use App\Repository\PrintRequestRepositoryInterface;
use Illuminate\Http\Request;

class PrintRequestController extends Controller
{
    private PrintRequestRepositoryInterface $printRequestRepository;
    private PrintRequestService $printRequestService;
    private CourseRepositoryInterface $courseRepository;
    private ExportService $export;

    public function __construct(PrintRequestRepositoryInterface $printRequestRepository , PrintRequestService $printRequestService , CourseRepositoryInterface $courseRepository , ExportService $export){
        $this->printRequestRepository = $printRequestRepository;
        $this->printRequestService = $printRequestService;
        $this->courseRepository = $courseRepository;
        $this->export = $export;
        $this->middleware('permission:printRequests-read')->only('indexBooks' , 'indexCertificates' , 'show');
        $this->middleware('permission:printRequests-update')->only('changeStatus');

    }

    public function indexBooks(){
        $requests = $this->printRequestRepository->getBooksRequests()->paginate(25);
        $courses = $this->courseRepository->getAll(['id' , 'name_ar' , 'name_en']);
        return view('dashboard.site.print_requests.index' , ['type' =>  'BOOK','requests' => $requests , 'courses' => $courses]);
    }


    public function indexCertificates(){
        $requests = $this->printRequestRepository->getCertificatesRequests()->paginate(25);
        $courses = $this->courseRepository->getAll(['id' , 'name_ar' , 'name_en']);

        return view('dashboard.site.print_requests.index' , ['type' => 'CERTIFICATE' ,'requests' => $requests , 'courses' => $courses]);
    }

    public function show($id){
        $request = $this->printRequestRepository->getById($id);
        return view('dashboard.site.print_requests.show' , ['request' => $request]);
    }

    public function changeStatus($id , PrintRequestRequest $request){
        return $this->printRequestService->changeStatus($id , $request);
    }

    public function destroy($id){
        return $this->printRequestService->delete($id);
    }

    public function exportBooks(string $type)
    {

        $requests = $this->printRequestRepository->getBooksRequests()->get();
        $data = [
            'requests' => $requests,
            'type' => 'book'

        ];

        return $this->export->handle('printRequests', 'dashboard.site.print_requests.export', $data, 'BooksRequests', $type);
    }

    public function exportCertificates(string $type)
    {
        $requests = $this->printRequestRepository->getCertificatesRequests()->get();
        $data = [
            'requests' => $requests,
            'type' => 'certificate'
        ];

        return $this->export->handle('printRequests', 'dashboard.site.print_requests.export', $data, 'CertificatesRequests', $type);
    }
}
