<?php

namespace App\Http\Controllers\Dashboard\Coupon;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Coupons\CouponRequest;
use App\Http\Services\Dashboard\Coupon\CouponService;
use App\Http\Services\Mutual\ExportService;
use App\Repository\CouponRepositoryInterface;
use App\Repository\CourseBookRepositoryInterface;
use App\Repository\CourseRepositoryInterface;
use Illuminate\Http\Request;

class CouponController extends Controller
{

    private CouponRepositoryInterface $couponRepository;
    private CouponService $couponService;
    private CourseRepositoryInterface $courseRepository;
    private CourseBookRepositoryInterface $courseBookRepository;
    private ExportService $export;

    public function __construct(
        CouponRepositoryInterface $couponRepository ,
        CouponService $couponService ,
        CourseRepositoryInterface $courseRepository,
        CourseBookRepositoryInterface $courseBookRepository,
        ExportService $export,
    )
    {
        $this->couponRepository = $couponRepository;
        $this->couponService = $couponService;
        $this->courseRepository = $courseRepository;
        $this->courseBookRepository = $courseBookRepository;
        $this->export = $export;
        $this->middleware('permission:coupons-read')->only('index');
        $this->middleware('permission:coupons-create')->only('create', 'store');
        $this->middleware('permission:courses-update')->only('edit', 'update');
        $this->middleware('permission:coupons-delete')->only('destroy');
    }

    public function index(){
        $coupons = $this->couponRepository->paginate(15 , [] , 'DESC');
        return view('dashboard.site.coupons.index' , ['coupons' => $coupons]);
    }

    public function show($id){
        $coupons = $this->couponRepository->getById($id);
        return view('dashboard.site.coupons.show' , ['coupon' => $coupons]);
    }

    public function create(){
        $courses = $this->courseRepository->getAll(['id' , 'name_ar' , 'name_en']);
        $books = $this->courseBookRepository->filterStoreBooks(columns : ['id', 'name_ar' , 'name_en']);
        return view('dashboard.site.coupons.create' , ['courses' => $courses , 'books' => $books]);
    }

    public function edit($id){
        $coupon = $this->couponRepository->getById($id);
        $courses = $this->courseRepository->getAll(['id' , 'name_ar' , 'name_en']);
        $books = $this->courseBookRepository->filterStoreBooks(columns : ['id', 'name_ar' , 'name_en']);
        return view('dashboard.site.coupons.edit' , [
            'coupon' => $coupon,
            'courses' => $courses,
            'books' => $books
        ]);

    }

    public function update($id , CouponRequest $request){
        return $this->couponService->update($id , $request);
    }

    public function store(CouponRequest $request){
        return $this->couponService->store($request);
    }

    public function destroy($id){
        return $this->couponService->delete($id);
    }


    public function export(string $type)
    {
        $coupons = $this->couponRepository->getAll();
        $data = [
            'coupons' => $coupons
        ];

        return $this->export->handle('coupons', 'dashboard.site.coupons.export', $data, 'coupons', $type);
    }
}
