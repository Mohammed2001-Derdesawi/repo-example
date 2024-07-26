<?php

namespace App\Http\Controllers\Dashboard\Exam;

use App\Http\Contracts\Exportable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\CourseBankQuestion\ExamBankQuestionsRequest;
use App\Http\Requests\Dashboard\Exam\ExamRequest;
use App\Http\Services\Dashboard\Exam\ExamService;
use App\Http\Services\Mutual\ExportService;
use App\Repository\AnswerUserRepositoryInterface;
use App\Repository\CourseBankQuestionRepositoryInterface;
use App\Repository\CourseRepositoryInterface;
use App\Repository\ExamRepositoryInterface;
use App\Repository\FieldRepositoryInterface;
use App\Repository\LectureRepositoryInterface;
use App\Repository\StandardRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ExamController extends Controller implements Exportable
{
    private ExamRepositoryInterface $examRepository;
    private ExamService $examService;
    private ExportService $export;
    private CourseRepositoryInterface $courseRepository;
    private FieldRepositoryInterface $fieldRepository;
    private LectureRepositoryInterface $lectureRepository;
    private StandardRepositoryInterface $standardRepository;
    private AnswerUserRepositoryInterface $answerUserRepository;

    protected CourseBankQuestionRepositoryInterface $questionRepository;
    public function __construct(
        ExamRepositoryInterface $examRepository,
        ExamService $examService,
        ExportService $exportService,
        CourseRepositoryInterface $courseRepository,
        FieldRepositoryInterface $fieldRepository,
        LectureRepositoryInterface $lectureRepository,
        StandardRepositoryInterface $standardRepository,
        AnswerUserRepositoryInterface $answerUserRepository,
        CourseBankQuestionRepositoryInterface $questionRepository
    )
    {
        $this->examRepository = $examRepository;
        $this->examService = $examService;
        $this->export = $exportService;
        $this->courseRepository = $courseRepository;
        $this->fieldRepository = $fieldRepository;
        $this->standardRepository = $standardRepository;
        $this->lectureRepository = $lectureRepository;
        $this->answerUserRepository = $answerUserRepository;
        $this->questionRepository = $questionRepository;
        $this->middleware('permission:exams-read')->only('index' , 'show');
        $this->middleware('permission:exams-create')->only('create', 'store');
        $this->middleware('permission:exams-update')->only('edit', 'update');
        $this->middleware('permission:exams-delete')->only('destroy');
    }

    public function index(){
        $exams = $this->examRepository->paginate(perPage: 15 , orderBy: 'DESC', addition: function ($query) {
            $query->whereHas('course', function ($query) {
                $query->whereHas('teachers', function ($query) {
                    $query->where('managers.id', auth()->id());
                });
            });
        }, filters: function ($query){
            if (\request()->has('type') && \request('type') != "ALL"){
                if (request('type') == 'COURSE'){
                    $query->where(['standard_id' => null , 'lecture_id' => null]);
                }elseif(request('type') == "STANDARD"){
                    $query->whereNull('lecture_id')->whereNotNull('standard_id');
                }else{
                    $query->whereNotNull('lecture_id');
                }
            }
            if (\request()->has('course') && \request('course') != "ALL"){
                $query->where('course_id' , \request('course'));
            }
        });
        $fields = $this->fieldRepository->getAll();
        return view('dashboard.site.exams.index' , ['exams' => $exams , 'fields' => $fields]);
    }

    public function create(){
        $courses = $this->courseRepository->getAll(['id' , 'name_ar' , 'name_en'] , addition: function ($query){
            $query->whereHas('teachers', function ($query) {
                $query->where('managers.id', auth()->id());
            });
        });
        $standards = $this->standardRepository->getAll(['id' , 'name_ar' , 'name_en' , 'course_id']);
        $lectures = $this->lectureRepository->getAll(['id' , 'name_ar' , 'name_en' , 'standard_id']);
        return view('dashboard.site.exams.create' , ['courses' => $courses , 'standards' => $standards , 'lectures' => $lectures]);
    }

    public function show($id){
        $exam = $this->examRepository->getById($id  , ['*'] , ['course' , 'standard' , 'lecture','questions'=>function($q){
            $q->orderBy('order');
        }]);
        $examNumbers = $this->answerUserRepository->getExamNumbersDetails($id);
        abort_unless(Gate::allows('control-course', $exam->course), 403);
        return view('dashboard.site.exams.show' , [
            'exam' => $exam,
            'examNumbers' => $examNumbers,

        ]);
    }

    public function store(ExamRequest $request){
        return $this->examService->store($request);
    }
    public function edit($id){
        $exam = $this->examRepository->getById($id);
        abort_unless(Gate::allows('control-course', $exam->course), 403);
        return view('dashboard.site.exams.edit' , ['exam' => $exam]);
    }

    public function update($id ,ExamRequest $request ){
        return $this->examService->update($id , $request);
    }

    public function destroy($id){
        return $this->examService->delete($id);
    }

    public function preview($id){
        return view('dashboard.site.exams.preview' , [
           'exam' => $this->examRepository->getById($id)
        ]);
    }


    public function export(string $type)
    {
        $exams = $this->examRepository->getAll(orderBy: 'DESC', addition: function ($query) {
            $query->whereHas('course', function ($query) {
                $query->whereHas('teachers', function ($query) {
                    $query->where('managers.id', auth()->id());
                });
            });
        }, filters: function ($query){
            if (\request()->has('type') && \request('type') != "ALL"){
                if (request('type') == 'COURSE'){
                    $query->where(['standard_id' => null , 'lecture_id' => null]);
                }elseif(request('type') == "STANDARD"){
                    $query->whereNull('lecture_id')->whereNotNull('standard_id');
                }else{
                    $query->whereNotNull('lecture_id');
                }
            }
            if (\request()->has('course') && \request('course') != "ALL"){
                $query->where('course_id' , \request('course'));
            }
        });

        $data = [
            'exams' => $exams,
        ];

        return $this->export->handle('exams', 'dashboard.site.exams.export', $data, 'exams', $type);
    }


    public function createBankQuestions($examId){

        $exam = $this->examRepository->getById($examId);
        $courses = $this->courseRepository->getAll(addition:function($q){
           return $q->whereHas('teachers',function($q){
            return $q->where('manager_id',auth()->user()->id);
           });
        });

        return view('dashboard.site.exams.bank_questions' , ['exam' => $exam,'courses' => $courses]);
    }


    public function storeBankQuestions(ExamBankQuestionsRequest $request,$examId){

      return $this->examService->storeBankQuestions($request,$examId);

    }


    public function getBankQuestionsByCourse(Request $request){
        return $this->questionRepository->getBankQuestionsByCourse($request);
    }

    public function reOrderQuestions(Request $request,$examId){
        $reorder=$this->examService->reOrderQuestions($request,$examId);
        return response()->json([],$reorder?200:500);
    }
}
