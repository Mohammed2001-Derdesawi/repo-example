<?php

namespace App\Http\Controllers\Dashboard\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Courses\TrainingBagRequest;
use App\Http\Services\Dashboard\Courses\CourseBookService;
use App\Repository\CourseBookRepositoryInterface;
use App\Repository\CourseRepositoryInterface;
use Illuminate\Http\Request;

class TrainingBagController extends Controller
{
    private CourseBookService $courseBookService;
    private CourseRepositoryInterface $courseRepository;
    private CourseBookRepositoryInterface $courseBookRepository;


    public function __construct( CourseBookService $courseBookService, CourseBookRepositoryInterface $courseBookRepository , CourseRepositoryInterface $courseRepository)
    {
        $this->courseBookService = $courseBookService;
        $this->courseRepository = $courseRepository;
        $this->courseBookRepository = $courseBookRepository;
        $this->middleware('permission:books-create')->only('create', 'store');
        $this->middleware('permission:books-update')->only('edit', 'update');
        $this->middleware('permission:books-delete')->only('destroy');
    }

    public function create(){
        $courses = $this->courseRepository->getAll(['id' , 'name_ar' , 'name_en'] , addition: function ($query){
            $query->whereHas('teachers', function ($query) {
                $query->where('managers.id', auth()->id());
            });
        });
        return view('dashboard.site.courses.TrainingBags.create' , ['courses' => $courses]);
    }

    public function store(TrainingBagRequest $request){
        return $this->courseBookService->storeTrainingBag($request);
    }

    public function edit($id){
        $courses = $this->courseRepository->getAll(['id' , 'name_ar' , 'name_en'] , addition: function ($query){
            $query->whereHas('teachers', function ($query) {
                $query->where('managers.id', auth()->id());
            });
        });
        $bag = $this->courseBookRepository->getById($id);
        return view('dashboard.site.courses.TrainingBags.edit' , ['bag' => $bag , 'courses' => $courses]);
    }

    public function update($id , TrainingBagRequest $request){
        return $this->courseBookService->updateTrainingBag($id , $request);
    }
}
