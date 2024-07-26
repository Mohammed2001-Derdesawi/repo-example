<?php

namespace App\Http\Services\Dashboard\Courses;

use App\Http\Requests\Dashboard\Courses\CourseRequest;
use App\Http\Requests\Dashboard\Fields\FieldRequest;
use App\Http\Services\Mutual\FileManagerService;
use App\Models\CourseTeacher;
use App\Repository\CourseRepositoryInterface;
use App\Repository\Eloquent\CourseBookRepository;
use App\Repository\FieldRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class CourseService
{
    private CourseRepositoryInterface $courseRepository;
    private CourseBookService $courseBookService;
    private CourseAttachmentService $courseAttachmentService;
    private FileManagerService $fileManager;

    public function __construct(
        CourseRepositoryInterface $courseRepository ,
        FileManagerService $fileManager ,
        CourseBookService $courseService ,
        CourseAttachmentService $courseAttachmentService
    )
    {
        $this->courseRepository = $courseRepository;
        $this->fileManager = $fileManager;
        $this->courseBookService = $courseService;
        $this->courseAttachmentService = $courseAttachmentService;
    }

    public function store(CourseRequest $request){
        $notcontinue = $request->notcontinue ? 1 : 0;
        $courseData = $request->except('books' , 'attachments' , 'teachers');
        $courseData['app_price'] =  $courseData['app_price'] == null ? $courseData['price'] : $courseData['app_price'];
        $courseData = array_merge($courseData,['notcontinue' => $notcontinue]);
        $courseData['certificate_price'] = $request['certificate_price'] ?? 0;
        DB::beginTransaction();
        try {
            $course = $this->storeCourse($courseData);
            $this->assignTeachersToCourse($request['teachers'] , $course);
            if ($request->has('books'))
                $this->courseBookService->storeBooks($request['books'] , $course['id']);
            if ($request->has('attachments'))
                $this->courseAttachmentService->storeAttachments($request['attachments'] , $course['id']);
            DB::commit();
            return redirect()->route('standards.create' , ['course_id' => $course['id']])->with(['success' => __('messages.Course created successfully , Please Assign Standards')]);
        }catch (\Exception $e){

//            dd($e);
            Log::info('courses create error::: ' . $e->getMessage());
            DB::rollBack();
            return back()->with(['error' => __('messages.Something went wrong')]);
        }
    }

    private function storeCourse($data){
        $data['is_active'] = isset($data['is_active']) && $data['is_active'] == 'on';
        $data['important_flag'] = isset($data['important_flag']) && $data['important_flag'] == 'on';
        $data['is_ratable'] = isset($data['is_ratable']) && $data['is_ratable'] == 'on';
        $data['request_certificate_available'] = isset($data['request_certificate_available']) && $data['request_certificate_available'] == 'on';
        $data['registration_status'] = isset($data['registration_status']) && $data['registration_status'] == 'on';
        $data['notcontinue'] = isset($data['notcontinue']) && $data['notcontinue'];
        $data['send_auto_whatsapp_message']=isset($data['send_auto_whatsapp_message']) && $data['send_auto_whatsapp_message']=='1'?true:false;
        $data['whatsapp_image_url']=isset($data['whatsapp_image_url'])? $data['send_auto_whatsapp_message']:null;
        $data['whatsapp_message']=isset($data['whatsapp_message'])? $data['whatsapp_message']:null;
        $data['is_certificate_avaliable']=isset($data['certificate_available']) && $data['certificate_available']=='on'? true:false;
        unset($data['certificate_available']); // remove certificate_avaliable from request
        if(isset($data['image'])){
            $data['image'] = $this->fileManager->handle('image', 'courses');
        }
        if(isset($data['profile_file'])){
            $data['profile_file'] = $this->fileManager->handle('profile_file', 'courses/profiles/');
        }
        if (isset($data['goals']))
            $data['goals'] = json_encode($data['goals']);
        return $this->courseRepository->create($data);
    }



    public function update($id , CourseRequest $request){
        $course = $this->courseRepository->getById($id);
        abort_unless(Gate::allows('control-course', $course), 401);
        DB::beginTransaction();
        try {
            $data = $request->except('teachers');
            $data['app_price'] =  $data['app_price'] == null ? $data['price'] : $data['app_price'];
            $data['certificate_price'] = $data['certificate_price'] ?? 0;
            $data['is_active'] = $data['is_active'] == 'on';
            $data['important_flag'] = $data['important_flag'] == 'on';
            $data['is_ratable'] = $data['is_ratable'] == 'on';
            $data['request_certificate_available'] = $data['request_certificate_available'] == 'on';
            $data['registration_status'] = $data['registration_status'] == 'on';
            $data['send_auto_whatsapp_message']=isset($data['send_auto_whatsapp_message']) && $data['send_auto_whatsapp_message']=='1'?true:false;
            $data['whatsapp_image_url']=isset($data['whatsapp_image_url'])? $data['whatsapp_image_url']:null;
            $data['whatsapp_message']=isset($data['whatsapp_message'])? $data['whatsapp_message']:null;
            $data['is_certificate_avaliable']=isset($data['certificate_available']) && $data['certificate_available']=='on'? true:false;
            unset($data['certificate_available']); // for remove certificate_avaliable
            $notcontinue = $request->notcontinue ? 1 : 0;
            if($notcontinue == 0)
            {
                $dayNumbers = null;
                $data = array_merge($data,['notcontinue' => $notcontinue , 'dayNumbers' => $dayNumbers]);
            }
            $data = array_merge($data,['notcontinue' => $notcontinue]);
            if($request->image !== null){
                $data['image'] = $this->fileManager->handle('image', 'courses');
            }
            if($request->profile_file !== null){
                $data['profile_file'] = $this->fileManager->handle('profile_file', 'courses/profiles/');
            }
            $this->courseRepository->update($id , $data);
            $this->assignTeachersToCourse($request['teachers'] , $course);
            DB::commit();
            return redirect()->route('courses.index')->with(['success' => __('messages.Course updated successfully')]);
        }catch (\Exception $e){
            DB::rollBack();
            return back()->with(['error' => __('messages.Something went wrong')]);
        }
    }

    public function delete($id) {
        $course = $this->courseRepository->getById($id);
        abort_unless(Gate::allows('control-course', $course), 401);
        if (Gate::allows('delete-course', $course)) {
            $this->courseRepository->delete($id);
            return redirect()->route('courses.index')->with(['success' => __('messages.Course deleted successfully')]);
        }
        return redirect()->route('courses.index')->with(['error' => __('messages.Course cannotBeDeleted')]);
    }

    private function assignTeachersToCourse($teachers , $course){
        DB::beginTransaction();
        try {
            $course->teachers()->sync($teachers);
            DB::commit();
            return true;
        }catch (\Exception $e){
            DB::rollBack();
            return $e;
        }
    }

}
