<?php

namespace App\Repository\Eloquent;

use App\Models\Category;
use App\Models\Exam;
use App\Models\Field;
use App\Repository\CategoryRepositoryInterface;
use App\Repository\ExamRepositoryInterface;
use App\Repository\FieldRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class ExamRepository extends Repository implements ExamRepositoryInterface
{
    protected Model $model;

    public function __construct(Exam $model){
        parent::__construct($model);
    }


    public function getCourseExams($id)
    {
        return $this->model::query()
            ->where(['course_id' => $id , 'is_active' => true])
            ->where('type', '!=', 'LECTURE')
            ->whereHas('questions', fn ($query) => $query->where('is_active', true))
            ->orderBy('created_at','DESC')
            ->get()
            ->groupBy('type')
            ->map(function ($exams, $type) {
                return [
                    'type' => $type,
                    'exams' => $exams,
                ];
            })
            ->values();
    }

    public function getCount(){
        return $this->model::query()->count();
    }

    public function getCourseExamsByType($course_id , $type){
        return $this->model::query()
            ->where(['course_id' => $course_id , 'type' => $type ,'is_active' => true])
            ->whereHas('questions', fn ($query) => $query->where('is_active', true))
            ->get();
    }

    public function getUserExams($user_id){
        return $this->model::query()->whereHas('users' , function ($query) use ($user_id) {
            $query->where('users.id' , $user_id);
        })->get();
    }
}
