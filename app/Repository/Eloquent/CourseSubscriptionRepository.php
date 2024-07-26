<?php

namespace App\Repository\Eloquent;

use App\Models\Category;
use App\Models\Course;
use App\Models\CourseAttachment;
use App\Models\CourseBook;
use App\Models\CourseInquiry;
use App\Models\CourseUser;
use App\Models\Exam;
use App\Models\Field;
use App\Repository\CategoryRepositoryInterface;
use App\Repository\CourseRepositoryInterface;
use App\Repository\CourseSubscriptionRepositoryInterface;
use App\Repository\FieldRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CourseSubscriptionRepository extends Repository implements CourseSubscriptionRepositoryInterface
{
    protected Model $model;

    public function __construct(CourseUser $model){
        parent::__construct($model);
    }

    public function getByUserId($user_id , $perPage = 9){
        return $this->model::where('user_id' , $user_id)->orderByDesc('id')->paginate($perPage);
    }

    public function getByCourseId($course_id , $perPage = 9){
        return $this->model::where('course_id' , $course_id)->orderByDesc('id')->paginate($perPage);
    }

    public function getAllowedRegisterMethod(){
        return [
            'admin',
            'cash',
            'tamara',
            'epayment'
        ];
    }






}
