<?php

namespace App\Http\Controllers\Dashboard\Student;

use App\Http\Contracts\Exportable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Student\StudentRequest;
use App\Http\Services\Dashboard\Student\StudentService;
use App\Http\Services\Mutual\ExportService;
use App\Models\User;
use App\Repository\CourseRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use Illuminate\Http\Request;

class StudentController extends Controller implements Exportable
{
    private UserRepositoryInterface $userRepository;
    private CourseRepositoryInterface $courseRepository;
    protected StudentService $student;
    protected ExportService $export;

    public function __construct(
        UserRepositoryInterface $userRepository,
        CourseRepositoryInterface $courseRepository,
        StudentService $studentService,
        ExportService $exportService,
    )
    {
        $this->middleware('permission:students-read')->only('index' , 'show');
        $this->middleware('permission:students-create')->only('create', 'store');
        $this->middleware('permission:students-delete')->only('destroy');
        $this->userRepository = $userRepository;
        $this->courseRepository = $courseRepository;
        $this->student = $studentService;
        $this->export = $exportService;
    }

    public function index()
    {
        $students = $this->userRepository->paginate(orderBy:"DESC" , filters: function ($query){
            if (request()->has('search') && request('search') != "" ){
                $query->where('name' , 'like', '%'.request('search').'%')->orWhere('email' , 'like', '%'.request('search').'%')
                    ->orWhere('phone' , 'like', '%'.request('search').'%');
            }
        });
        return view('dashboard.site.student.index', compact('students'));
    }

    public function create()
    {
        return view('dashboard.site.student.create');
    }

    public function store(StudentRequest $request)
    {
        return $this->student->store($request);
    }

    public function show($id)
    {
        $student = $this->userRepository->getById($id);
        $unSubscribedCourses = $this->courseRepository->getUnSubscribedStudentCourses($id);

        return view('dashboard.site.student.show', compact('student', 'unSubscribedCourses'));
    }

    public function edit($id)
    {
        $student = $this->userRepository->getById($id);
        return view('dashboard.site.student.edit', compact('student'));
    }

    public function update(StudentRequest $request, $id)
    {
        return $this->student->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->student->delete($id);
    }

    public function loginFromAdmin($id){
         auth('api')->login(User::find($id));
         $token = auth('api')->user()->token();
         return redirect()->to(env('APP_URL')."/dashboard-login?token=" . $token);
    }

    public function export(string $type) {
        $students = $this->userRepository->getAll(filters: function ($query){
            if (request()->has('search') && request('search') != "" ){
                $query->where('name' , 'like', '%'.request('search').'%')->orWhere('email' , 'like', '%'.request('search').'%')
                    ->orWhere('phone' , 'like', '%'.request('search').'%');
            }
        });

        $data = [
            'students' => $students,
        ];

        return $this->export->handle('students', 'dashboard.site.student.export', $data, 'students', $type);
    }
}
