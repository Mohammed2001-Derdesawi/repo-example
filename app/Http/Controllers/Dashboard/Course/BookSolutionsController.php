<?php

namespace App\Http\Controllers\Dashboard\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Courses\BookRequest;
use App\Http\Requests\Dashboard\Courses\BookSolutionRequest;
use App\Http\Services\Dashboard\Courses\BookSolutionService;
use App\Http\Services\Dashboard\Courses\CourseBookService;
use App\Http\Services\Mutual\FileManagerService;
use App\Repository\CourseBookRepositoryInterface;
use App\Repository\CourseBookSolutionRepositoryInterface;
use App\Repository\CourseRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BookSolutionsController extends Controller
{
    private BookSolutionService $bookSolution;
    private CourseBookSolutionRepositoryInterface $courseBookSolutionRepository;

    public function __construct(BookSolutionService $bookSolution , CourseBookSolutionRepositoryInterface $courseBookSolutionRepository){
        $this->bookSolution = $bookSolution;
        $this->courseBookSolutionRepository = $courseBookSolutionRepository;
        $this->middleware('permission:solutions-create')->only(['create', 'store']);
        $this->middleware('permission:solutions-update')->only(['edit', 'update']);
        $this->middleware('permission:solutions-delete')->only('destroy');

    }

    public function create($id){
        return view('dashboard.site.courses.solutions.create' , [
            'course_id' => $id
        ]);
    }

    public function store(BookSolutionRequest $request){
        return $this->bookSolution->storeSolution($request);
    }

    public function edit($course_id , $id){
        $solution = $this->courseBookSolutionRepository->getById($id);
        abort_unless(Gate::allows('control-course', $solution->course), 403);
        return view('dashboard.site.courses.solutions.edit' , [
            'solution' => $solution
        ]);
    }

    public function update($course_id , $id , BookSolutionRequest $request){
        return $this->bookSolution->update($id , $request);
    }

    public function destroy($course_id , $id){
        return $this->bookSolution->delete($id);
    }

}
