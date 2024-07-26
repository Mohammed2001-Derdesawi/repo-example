<?php

namespace App\Http\Controllers\Dashboard\Lecture;

use App\Http\Contracts\Exportable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Lecture\LectureRequest;
use App\Http\Services\Dashboard\Lecture\LectureService;
use App\Http\Services\Mutual\ExportService;
use App\Repository\CourseRepositoryInterface;
use App\Repository\FieldRepositoryInterface;
use App\Repository\LectureRepositoryInterface;
use App\Repository\StandardRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LectureController extends Controller implements Exportable
{

    protected LectureService $lecture;
    private CourseRepositoryInterface $courseRepository;
    private LectureRepositoryInterface $lectureRepository;
    private FieldRepositoryInterface $fieldRepository;
    private StandardRepositoryInterface $standardRepository;
    private ExportService $export;

    public function __construct(
        LectureService $lectureService,
        CourseRepositoryInterface $courseRepository,
        LectureRepositoryInterface $lectureRepository,
        FieldRepositoryInterface $fieldRepository,
        StandardRepositoryInterface $standardRepository,
        ExportService $exportService,
    )
    {
        $this->middleware('auth');
        $this->middleware('permission:lectures-read')->only('index' , 'show');
        $this->middleware('permission:lectures-create')->only('create', 'store');
        $this->middleware('permission:lectures-delete')->only('destroy');
        $this->lecture = $lectureService;
        $this->courseRepository = $courseRepository;
        $this->lectureRepository = $lectureRepository;
        $this->fieldRepository = $fieldRepository;
        $this->standardRepository = $standardRepository;
        $this->export = $exportService;
    }

    public function index()
    {
        $lectures = $this->lectureRepository->paginate(perPage: 10, orderBy: "DESC" , addition: function ($query) {
            $query->whereHas('standard', function ($query) {
                $query->whereHas('course', function ($query) {
                    $query->whereHas('teachers', function ($query) {
                        $query->where('managers.id', auth()->id());
                    });
                });
            });
        }, filters: function ($query){
            if (\request()->has('course') && \request('course') != "ALL"){
                $query->whereHas('standard' , function ($query){
                   $query->where('course_id' , request('course'));
                });
            }
        });
        $fields = $this->fieldRepository->getAll();
        return view('dashboard.site.lectures.index', ['lectures' => $lectures , 'fields' => $fields]);
    }

    public function create()
    {
        $courses = $this->courseRepository->getAllWhereHasStandards();
        $standards = $this->standardRepository->getAll(['id' , 'name_ar' , 'name_en' , 'course_id']);
        return view('dashboard.site.lectures.create', ['courses' => $courses, 'standards' => $standards]);
    }

    public function store(LectureRequest $request)
    {
        return $this->lecture->store($request);
    }

    public function show($id)
    {
        $lecture = $this->lectureRepository->getById($id);
        abort_unless(Gate::allows('control-course', $lecture->standard->course), 403);
        return view('dashboard.site.lectures.show', ['lecture' => $lecture]);
    }

    public function edit($id)
    {
        $courses = $this->courseRepository->getAllWhereHasStandards();
        $standards = $this->standardRepository->getAll(['id' , 'name_ar' , 'name_en' , 'course_id']);
        $lecture = $this->lectureRepository->getById($id);
        abort_unless(Gate::allows('control-course', $lecture->standard->course), 403);
        return view('dashboard.site.lectures.edit', ['courses' => $courses, 'lecture' => $lecture, 'standards' => $standards]);
    }

    public function update(LectureRequest $request, $id)
    {
        return $this->lecture->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->lecture->destroy($id);
    }

    public function export(string $type) {
        $lectures = $this->lectureRepository->getAll(orderBy: "DESC" , addition: function ($query) {
            $query->whereHas('standard', function ($query) {
                $query->whereHas('course', function ($query) {
                    $query->whereHas('teachers', function ($query) {
                        $query->where('managers.id', auth()->id());
                    });
                });
            });
        }, filters: function ($query){
            if (\request()->has('course') && \request('course') != "ALL"){
                $query->whereHas('standard' , function ($query){
                    $query->where('course_id' , request('course'));
                });
            }
        });

        $data = [
            'lectures' => $lectures,
        ];

        return $this->export->handle('lectures', 'dashboard.site.lectures.export', $data, 'lectures', $type);
    }

    public function fetch(Request $request) {
        return $this->lectureRepository->get('standard_id', $request->input('standard_id') ?? null);
    }
}
