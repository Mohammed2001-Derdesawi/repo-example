<?php

namespace App\Repository\Eloquent;

use App\Models\Course;
use App\Repository\CourseRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class CourseRepository extends Repository implements CourseRepositoryInterface
{
    protected Model $model;

    public function __construct(Course $model)
    {
        parent::__construct($model);
    }

    public function getActiveById($modelId, array $columns = ['*'], array $relations = [], array $appends = []): ?Model
    {
        return $this->model::query()->select($columns)->where('is_active', true)->with($relations)->findOrFail($modelId)->append($appends);
    }


    public function getAllWhereHasStandards() {
        return $this->model::query()->where(function ($query){
            $query->whereHas('standards');
        })->where(function ($query){
            if (!(auth()->user()->hasRole(['super-admin', 'admin']))){
                $query->whereHas('teachers', function ($query) {
                    $query->where('managers.id', auth()->id());
                });
            }})->get();
    }

    public function filterCourses($perPage = 9 , $columns = ['*']){
        $courses = $this->model::query()->where('registration_status' , true);
        if (\request()->has('search') && \request()->search !== null ){
            $courses->where(function ($query){
                $query->where('name_ar' , 'LIKE' , '%' . \request()->search .'%')
                    ->orWhere('name_en' , 'LIKE' , '%' . \request()->search .'%');
            });
        }

        if (\request()->has('field_id') && \request()->field_id != null){
            $courses->whereHas('category' , function ($query) {
                $query->where('field_id' , \request()->field_id);
            });
        }

        if (\request()->has('course_time') && \request()->course_time != null && \request()->course_time != 2){
            $currentDate = date('Y-m-d');
            if (\request()->course_time == 0)
                $courses->whereDate('start_date' , '<=' , $currentDate);
            else
                $courses->whereDate('start_date' , '>' , $currentDate);


        }

        if (\request()->has('price') && \request()->price != null && request()->price != 2){
            if (\request()->price == 0)
                $courses->where('price'  , 0);
            else
                $courses->where('price' , '>' , 0);
        }

        if (\request()->has('category_id') && \request()->category_id != null){
            $courses->where('category_id' , \request()->category_id);
        }

        if (\request()->has('order_by') && \request()->order_by != null){
            $courses->orderBy('id' , \request()->order_by);
        }else{
            $courses->orderByDesc('id');
        }
        return $courses->select($columns)
            ->with('teachers:id,name,image')
            ->where('is_active' , true)
            ->paginate($perPage);
    }

    public function getUserCoursesByProgress($user_id , $type = "PROGRESS"){
        $courses =  $this->model::query()->where('is_active', true)->whereHas('subscriptions' , function ($query) use ($user_id){
            $query->where('user_id' , $user_id)->where('is_active' , true);
        })->orderByDesc('id')->get();
        if ($type != "ALL"){
            $courses = $courses->filter(function ($item) use ($type){
                if ($type == "PROGRESS")
                    return Carbon::parse($item->end_date)->isFuture() || Carbon::parse($item->end_date)->isToday();
                else
                    return !(Carbon::parse($item->end_date)->isFuture() || Carbon::parse($item->end_date)->isToday());
            });
        }
        return $courses;
    }

    public function getRequestableCertificates() {
        return $this->model::query()
            ->whereNotNull('certificate_price')
            ->where('request_certificate_available', true)
            ->where(function ($query) {
                $query->whereHas('subscriptions', function ($query) {
                    $query->where('user_id', auth('api')->id());
                });
            })
            ->get();
    }

//    public function getCurrentCourses(){
//        $currentDate = date('Y-m-d');
//        return $this->model::query()
//            ->whereDate('start_date' , '<=' , $currentDate)
//            ->whereDate('end_date' , '>=' , $currentDate)
//            ->get();
//
//    }

    public function getImportantCourses(){
        $courses = $this->model::query()->where('important_flag' , true)->where('is_active' , true)->where('registration_status' , true);
        if (\request()->has('field_id')){
            $courses->whereHas('category' , function ($query) {
                $query->where('field_id' , \request()->field_id);
            });
        }
        return $courses->get();

    }

    public function getUnSubscribedStudentCourses($student_id){
        return $this->model::query()->whereDoesntHave('users' , function ($query) use ($student_id){
           $query->where('users.id' , $student_id);
        })->select('id' , 'name_ar' , 'name_en')->get();
    }

    public function isExisted($course_id) {
        return $this->model::query()->where('courses.id', $course_id)->where('is_active', 1)->exists();
    }


    public function whereHasSubscribes(){
        return $this->model::query()->whereHas('subscriptions' , function ($query){
            $query->whereNotNull('payment_id');
        })->select(['id' , 'name_ar' , 'name_en'])->get();
    }

    public function coursesCount(){
        return $this->model::query()->count();
    }

    public function getUnSubscribesCourseExams($id){
        return $this->model::query()->find($id)->exams()->whereHas('questions', fn ($query) => $query->where('is_active', true))->where('is_active' , true)->where('type' , '!=' , 'LECTURE')->get();
    }

    public function canBeRegistered($id){
        return $this->model::query()->select('id' , 'registration_status')->find($id)->registration_status;
    }

    public function isUserSubscribed($student_id,$course_id,$columns=['*']){
        return $this->model::query()->whereHas('users' , function ($query) use ($student_id){
            $query->where('users.id' , $student_id);
         })->select($columns)->find($course_id)?true: false;
    }

    public function getCourseFromCoursesByPrice($courses,$ord='ASC'){
        return $this->model::query()->whereIn('id',$courses)->orderBy('price',$ord)->first();
    }
}
