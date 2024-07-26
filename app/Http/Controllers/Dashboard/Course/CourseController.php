<?php

namespace App\Http\Controllers\Dashboard\Course;

use App\Http\Contracts\Exportable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Courses\CourseRequest;
use App\Http\Services\Dashboard\Courses\CourseService;
use App\Http\Services\Mutual\ExportService;
use App\Repository\CourseBankQuestionRepositoryInterface;
use App\Repository\CourseRepositoryInterface;
use App\Repository\CategoryRepositoryInterface;
use App\Repository\FieldRepositoryInterface;
use App\Repository\ManagerRepositoryInterface;
use Illuminate\Support\Facades\Gate;

class CourseController extends Controller implements Exportable
{

    private CourseRepositoryInterface $courseRepository;
    private CategoryRepositoryInterface $categoryRepository;
    private FieldRepositoryInterface $fieldRepository;
    private ManagerRepositoryInterface $managerRepository;
    private CourseService $courseService;
    private ExportService $export;
    private CourseBankQuestionRepositoryInterface $questionRepository;


    public function __construct(
        CourseRepositoryInterface $courseRepository ,
        CategoryRepositoryInterface $categoryRepository ,
        FieldRepositoryInterface $fieldRepository,
        CourseService $courseService ,
        ManagerRepositoryInterface $managerRepository,
        ExportService $exportService,
        CourseBankQuestionRepositoryInterface $questionRepository
    )
    {
        $this->courseRepository = $courseRepository;
        $this->categoryRepository = $categoryRepository;
        $this->fieldRepository = $fieldRepository;
        $this->courseService = $courseService;
        $this->managerRepository = $managerRepository;
        $this->export = $exportService;
        $this->questionRepository = $questionRepository;
        $this->middleware('permission:courses-read')->only('index' , 'show');
        $this->middleware('permission:courses-create')->only('create', 'store');
        $this->middleware('permission:courses-update')->only('edit', 'update');
        $this->middleware('permission:courses-delete')->only('destroy');
    }

    public function index(){
        $courses = $this->courseRepository->paginate(perPage: 15, addition: function ($query) {
            $query->where('is_active', true);
            $query->whereHas('teachers', function ($query) {
                $query->where('managers.id', auth()->id());
            });
        } , filters: function ($query){
            if (\request()->has('field') && \request('field') != "ALL"){
                $query->whereHas('category' , function ($query){
                    $query->where('field_id' , \request('field'));
                });
            }
        });
        $trasnferCourses=$this->courseRepository->paginate(perPage:0,columns:['id','name_ar','name_en']);
        $fields = $this->fieldRepository->getAll();
        return view('dashboard.site.courses.index' , ['courses' => $courses , 'fields' => $fields,'trasnferCourses'=>$trasnferCourses]);
    }

    public function show($id){
        $course = $this->courseRepository->getById($id , ['*'] , ['category' , 'books' , 'attachments', 'standards']);

        // $course_bank_questions = $this->questionRepository->get('course_id',$course->id);
        abort_unless(Gate::allows('control-course', $course), 403);
        return view('dashboard.site.courses.show' , ['course' => $course]);
    }

    public function create(){
        $categories = $this->categoryRepository->getAll(['id' , 'name_ar' , 'name_en']);
        $teachers = $this->managerRepository->getAll(['id' , 'name']);
        $cooperators = $this->managerRepository->getAll(['id' , 'name']);
        return view('dashboard.site.courses.create' , ['categories' => $categories , 'teachers' => $teachers , 'cooperators' => $cooperators ]);
    }

    public function store(CourseRequest $request){
        return $this->courseService->store($request);
    }

    public function edit($id){
        $course = $this->courseRepository->getById($id);
        abort_unless(Gate::allows('control-course', $course), 403);
        $categories = $this->categoryRepository->getAll(['id' , 'name_ar' , 'name_en']);
        $teachers = $this->managerRepository->getAll(['id' , 'name']);
        $cooperators = $this->managerRepository->getAll(['id' , 'name']);
        return view('dashboard.site.courses.edit' , ['course' => $course , 'categories' => $categories , 'teachers' => $teachers , 'cooperators' => $cooperators]);
    }

    public function update($id , CourseRequest $request){
        return $this->courseService->update($id , $request);
    }

    public function destroy($id){
        return $this->courseService->delete($id);
    }

    public function export(string $type)
    {
        $courses = $this->courseRepository->getAll(addition: function ($query) {
            $query->where('is_active', true);
            $query->whereHas('teachers', function ($query) {
                $query->where('managers.id', auth()->id());
            });
        } , filters: function ($query){
            if (\request()->has('field') && \request('field') != "ALL"){
                $query->whereHas('category' , function ($query){
                    $query->where('field_id' , \request('field'));
                });
            }
        });

        $data = [
            'courses' => $courses
        ];

        return $this->export->handle('courses', 'dashboard.site.courses.export', $data, 'courses', $type);
    }

    public function bankQuestions($id){
        $course = $this->courseRepository->getById($id ,['id','name_ar','name_en']);
        $course_bank_questions = $this->questionRepository->paginate(filters:function($q) use ($course){
            $q->where('course_id',$course->id);
        });
        abort_unless(Gate::allows('control-course', $course), 403);
        return view('dashboard.site.courses.BankQuestions.index' ,['course'=>$course,'course_bank_questions'=>$course_bank_questions]);
    }
}
