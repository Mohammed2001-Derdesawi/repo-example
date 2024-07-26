<?php

namespace App\Http\Services\Dashboard\Courses;

use App\Http\Requests\Dashboard\Courses\AttachmentRequest;
use App\Http\Requests\Dashboard\Courses\CourseRequest;
use App\Http\Requests\Dashboard\Fields\FieldRequest;
use App\Http\Services\Mutual\FileManagerService;
use App\Repository\CourseAttachmentRepositoryInterface;
use App\Repository\CourseRepositoryInterface;
use App\Repository\Eloquent\CourseAttachmentRepository;
use App\Repository\Eloquent\CourseBookRepository;
use App\Repository\FieldRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class CourseAttachmentService
{
    private CourseAttachmentRepositoryInterface $courseAttachmentRepository;
    private FileManagerService $fileManager;

    public function __construct(CourseAttachmentRepositoryInterface $courseAttachmentRepository , FileManagerService $fileManager){
        $this->courseAttachmentRepository = $courseAttachmentRepository;
        $this->fileManager = $fileManager;
    }

    public function storeAttachments($attachments , $course_id){
        DB::beginTransaction();
        try {
            foreach ($attachments as $key => $attachment){
                if(isset($attachment['file'])){
                    $attachment['file'] = $this->fileManager->handle('attachments.' . $key . '.file', 'courses\attachments');
                }
                $attachment['is_active'] = isset($attachment['is_active']);
                $this->courseAttachmentRepository->create([
                    'course_id' => $course_id,
                    'file' => $attachment['file'],
                    'name_ar' => $attachment['name_ar'],
                    'name_en' => $attachment['name_en'],
                    'is_active' => $attachment['is_active']
                ]);
            }
            DB::commit();
            return to_route('courses.show' , $course_id)->with('success' , __('messages.Attachment Added successfully'));
        }catch (\Exception $e){
//            return $e->getMessage();
            DB::rollBack();
            return back()->with(['error' => __('messages.Something went wrong')]);
        }
    }


    public function update($id , AttachmentRequest $request){
        $attachment = $this->courseAttachmentRepository->getById($id);
        abort_unless(Gate::allows('control-course', $attachment->course), 401);
        DB::beginTransaction();
        try {
            $data = $request['attachments'][0];
            if(isset($data['file'])){
                $data['file'] = $this->fileManager->handle('attachments.0.file', 'courses/attachments');
            }
            $data['is_active'] = isset($data['is_active']);
            $this->courseAttachmentRepository->update($id , $data);
            DB::commit();
            return redirect()->route('courses.show' , $attachment['course_id'])->with(['success' => __('messages.Attachment updated successfully')]);
        }catch (\Exception $e){
            DB::rollBack();
            return back()->with(['error' => __('messages.Something went wrong')]);
        }
    }

    public function delete($id) {
        $attachment = $this->courseAttachmentRepository->getById($id);
        abort_unless(Gate::allows('control-course', $attachment->course), 401);
        try {
            $attachment->delete();
            return back()->with(['success' => __('messages.Attachment deleted successfully')]);
        }catch (\Exception $e){
            return back()->with(['error' => __('messages.Something went wrong')]);
        }


    }


}
