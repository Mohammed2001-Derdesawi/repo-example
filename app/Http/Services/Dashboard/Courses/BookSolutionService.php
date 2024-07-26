<?php

namespace App\Http\Services\Dashboard\Courses;

use App\Http\Requests\Dashboard\Courses\AttachmentRequest;

use App\Http\Requests\Dashboard\Courses\BookSolutionRequest;
use App\Repository\CourseBookSolutionRepositoryInterface;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class BookSolutionService
{
    private CourseBookSolutionRepositoryInterface $courseBookSolutionRepository;

    public function __construct(CourseBookSolutionRepositoryInterface $courseBookSolutionRepository){
        $this->courseBookSolutionRepository = $courseBookSolutionRepository;
    }


    public function storeSolution(BookSolutionRequest $request){
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $this->courseBookSolutionRepository->create($data);
            DB::commit();
            return to_route('courses.show' , $data['course_id'])->with('success' , __('messages.Solution Added successfully'));
        }catch (\Exception $e){
            DB::rollBack();
            return back()->with(['error' => __('messages.Something went wrong')]);
        }
    }


    public function update($id , BookSolutionRequest $request){
        $solution = $this->courseBookSolutionRepository->getById($id);
        abort_unless(Gate::allows('control-course', $solution->course), 401);
        DB::beginTransaction();
        try {
           $data = $request->validated();
           $this->courseBookSolutionRepository->update($id , $data);
            DB::commit();
            return redirect()->route('courses.show' , $data['course_id'])->with(['success' => __('messages.Solution updated successfully')]);
        }catch (\Exception $e){
            DB::rollBack();
            return back()->with(['error' => __('messages.Something went wrong')]);
        }
    }

    public function delete($id) {
        $solution = $this->courseBookSolutionRepository->getById($id);
        abort_unless(Gate::allows('control-course', $solution->course), 401);
        try {
            $this->courseBookSolutionRepository->delete($id);
            return back()->with(['success' => __('messages.Solution deleted successfully')]);
        }catch (\Exception $e){
            return back()->with(['error' => __('messages.Something went wrong')]);
        }


    }


}
