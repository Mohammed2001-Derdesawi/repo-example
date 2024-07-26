<?php

namespace App\Http\Controllers\Dashboard\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Courses\AttachmentRequest;
use App\Http\Requests\Dashboard\Courses\BookRequest;
use App\Http\Services\Dashboard\Courses\CourseAttachmentService;
use App\Http\Services\Dashboard\Courses\CourseBookService;
use App\Http\Services\Mutual\FileManagerService;
use App\Repository\CourseAttachmentRepositoryInterface;
use App\Repository\CourseBookRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AttachmentController extends Controller
{
    private CourseAttachmentService $courseAttachmentService;
    private CourseAttachmentRepositoryInterface $courseAttachmentRepository;

    public function __construct( CourseAttachmentService $courseAttachmentService , CourseAttachmentRepositoryInterface $courseAttachmentRepository)
    {
        $this->courseAttachmentService = $courseAttachmentService;
        $this->courseAttachmentRepository = $courseAttachmentRepository;
        $this->middleware('permission:attachments-create')->only('create', 'store');
        $this->middleware('permission:attachments-update')->only('edit', 'update');
        $this->middleware('permission:attachments-delete')->only('destroy');
    }

    public function create($course_id){
        return view('dashboard.site.attachments.create' , ['course_id' => $course_id]);
    }

    public function store(AttachmentRequest $request , $course_id){
       return $this->courseAttachmentService->storeAttachments($request['attachments'] ,$course_id);
    }

    public function edit($course_id, $id){
        $attachment = $this->courseAttachmentRepository->getById($id);
        abort_unless(Gate::allows('control-course', $attachment->course), 403);
        return view('dashboard.site.attachments.edit' , ['attachment' => $attachment]);
    }

    public function update(AttachmentRequest $request, $course_id, $id){
        return $this->courseAttachmentService->update($id , $request);
    }

    public function destroy($course_id, $id){
        return $this->courseAttachmentService->delete($id);
    }
}
