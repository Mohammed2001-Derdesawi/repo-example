<?php

namespace App\Http\Controllers\Dashboard\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Courses\BookRequest;
use App\Http\Services\Dashboard\Courses\CourseBookService;
use App\Http\Services\Mutual\ExportService;
use App\Http\Services\Mutual\FileManagerService;
use App\Repository\CourseBookRepositoryInterface;
use App\Repository\CourseRepositoryInterface;
use App\Repository\FieldRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BookController extends Controller
{
    private CourseBookService $courseBookService;
    private FieldRepositoryInterface $fieldRepository;
    private CourseBookRepositoryInterface $courseBookRepository;
    private CourseRepositoryInterface $courseRepository;
    private ExportService $export;


    public function __construct( CourseBookService $courseBookService , FieldRepositoryInterface $fieldRepository  , CourseBookRepositoryInterface $courseBookRepository , CourseRepositoryInterface $courseRepository ,         ExportService $exportService,
    )
    {
        $this->courseBookService = $courseBookService;
        $this->fieldRepository = $fieldRepository;
        $this->courseBookRepository = $courseBookRepository;
        $this->courseRepository = $courseRepository;
        $this->export = $exportService;

        $this->middleware('permission:books-create')->only('create', 'store');
        $this->middleware('permission:books-read')->only('index');
        $this->middleware('permission:books-update')->only('edit', 'update');
        $this->middleware('permission:books-delete')->only('destroy');
    }

    public function index(){
        $books = $this->courseBookRepository->paginate(perPage: 15 , orderBy: 'DESC'
//            , addition: function ($query) {
//            $query->whereHas('course', function ($query) {
//                $query->whereHas('teachers', function ($query) {
//                    $query->where('managers.id', auth()->id());
//                });
//            });}
 , filters: function ($query){
//            if (\request()->has('store') && \request('store') != "ALL"){
//               $query->where('show_in_store' , \request('store'));
//            }
//
//            if (\request()->has('course') && \request('course') != "ALL"){
//                $query->where('course_id' , \request('course'));
//            }
//            $query->where('show_in_store' , true);
        });
        $fields = $this->fieldRepository->getAll();
        return view('dashboard.site.books.index' , ['books' => $books , 'fields' => $fields ]);
    }

    public function create(){
//        $courses = $this->courseRepository->getAll(['id' , 'name_ar' , 'name_en'] , addition: function ($query){
//            $query->whereHas('teachers', function ($query) {
//                $query->where('managers.id', auth()->id());
//            });
//        });
        return view('dashboard.site.books.create');
    }

    public function store(BookRequest $request){
       return $this->courseBookService->storeBooks($request['books'] ,$request['course_id']);
    }

    public function edit($id){
//        $courses = $this->courseRepository->getAll(['id' , 'name_ar' , 'name_en'] , addition: function ($query){
//            $query->whereHas('teachers', function ($query) {
//                $query->where('managers.id', auth()->id());
//            });
//        });
        $book = $this->courseBookRepository->getById($id);
//        if ($book->course){
//            abort_unless(Gate::allows('control-course', $book->course), 403);
//        }
        return view('dashboard.site.books.edit' , ['book' => $book]);
    }

    public function update(BookRequest $request, $id){
        return $this->courseBookService->update($id , $request);
    }

    public function destroy($id){
        return $this->courseBookService->delete($id);
    }

    public function export(string $type)
    {
        $books = $this->courseBookRepository->getAll();
        $data = [
            'books' => $books
        ];

        return $this->export->handle('books', 'dashboard.site.books.export', $data, 'books', $type);
    }
}
