<?php

namespace App\Http\Controllers\Dashboard\Standard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Standard\StandardRequest;
use App\Http\Services\Dashboard\Standard\StandardService;
use App\Http\Services\Mutual\ExportService;
use App\Repository\CourseRepositoryInterface;
use App\Repository\FieldRepositoryInterface;
use App\Repository\StandardRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class StandardController extends Controller
{
    private StandardRepositoryInterface $standardRepository;
    private CourseRepositoryInterface $courseRepository;
    private FieldRepositoryInterface $fieldRepository;
    protected StandardService $standard;
    protected ExportService $export;

    public function __construct(
        StandardRepositoryInterface $standardRepository,
        CourseRepositoryInterface $courseRepository,
        FieldRepositoryInterface $fieldRepository,
        StandardService $standardService,
        ExportService $exportService,
    )
    {
        $this->middleware('auth');
        $this->middleware('permission:standards-read')->only('index' , 'show');
        $this->middleware('permission:standards-create')->only('create', 'store');
        $this->middleware('permission:standards-delete')->only('destroy');
        $this->standardRepository = $standardRepository;
        $this->courseRepository = $courseRepository;
        $this->fieldRepository = $fieldRepository;
        $this->standard = $standardService;
        $this->export = $exportService;
    }

    public function index()
    {
        $standards = $this->standardRepository->paginate(perPage: 15, addition: function ($query) {
            $query->whereHas('course', function ($query) {
                $query->whereHas('teachers', function ($query) {
                    $query->where('managers.id', auth()->id());
                });
            });
        }, filters: function ($query){
            if (\request()->has('course') && \request('course') != "ALL"){
               $query->where('course_id' , \request('course'));
            }
        });
        $fields = $this->fieldRepository->getAll();
        return view('dashboard.site.standards.index', ['standards' => $standards , 'fields' => $fields]);
    }

    public function show($id) {
        $standard = $this->standardRepository->getById($id, relations: ['course', 'lectures']);
        abort_unless(Gate::allows('control-course', $standard->course), 403);
        return view('dashboard.site.standards.show', ['standard' => $standard]);
    }

    public function create()
    {
        $courses = $this->courseRepository->getAll(['id', 'name_ar', 'name_en'] , addition: function ($query){
            $query->whereHas('teachers', function ($query) {
                $query->where('managers.id', auth()->id());
            });
        });
        return view('dashboard.site.standards.create', ['courses' => $courses]);
    }

    public function store(StandardRequest $request)
    {
        return $this->standard->store($request);
    }

    public function edit($id)
    {
        $standard = $this->standardRepository->getById($id);
        abort_unless(Gate::allows('control-course', $standard->course), 403);
        $courses = $this->courseRepository->getAll(['id', 'name_ar', 'name_en'] , addition: function ($query){
            $query->whereHas('teachers', function ($query) {
                $query->where('managers.id', auth()->id());
            });
        });
        return view('dashboard.site.standards.edit', [
            'standard' => $standard,
            'courses' => $courses,
        ]);
    }

    public function update(StandardRequest $request, $id)
    {
        return $this->standard->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->standard->destroy($id);
    }

    public function getByCourseId(Request $request){
        return $this->standardRepository->getByCourseId($request['course_id']);
    }

    public function export(string $type) {
        $standards = $this->standardRepository->getAll(addition: function ($query) {
            $query->whereHas('course', function ($query) {
                $query->whereHas('teachers', function ($query) {
                    $query->where('managers.id', auth()->id());
                });
            });
        }, filters: function ($query){
            if (\request()->has('course') && \request('course') != "ALL"){
                $query->where('course_id' , \request('course'));
            }
        });

        $data = [
            'standards' => $standards,
        ];

        return $this->export->handle('standards', 'dashboard.site.standards.export', $data, 'standards', $type);
    }

    public function fetch(Request $request) {
        return $this->standardRepository->get('course_id', $request->input('course_id') ?? null);
    }
}
