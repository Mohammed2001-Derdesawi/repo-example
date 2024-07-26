<?php

namespace App\Repository\Eloquent;

use App\Models\CourseBook;
use App\Models\CourseInquiry;
use App\Repository\CourseBookRepositoryInterface;
use App\Repository\CourseInquireRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CourseInquireRepository extends Repository implements CourseInquireRepositoryInterface
{
    protected Model $model;
    protected $expectedRoutesForAdditions=['inquiries.destroySelected','inquiries.updateTypeSelected'];

    public function __construct(CourseInquiry $model)
    {
        parent::__construct($model);
    }

    public function getCourseInquiries($id){
        return $this->model::query()
            ->where('type', 'EDUCATIONAL')
            ->where('course_id', $id)
            ->where(function ($query) {
                $query->whereNotNull('answer');
                $query->orWhereNotNull('answer_voice');
            })
            ->where(function ($query) use ($id) {
                $query->where('is_public', true);
                $query->orWhere(function ($query) {
                    if (auth('api')->check()) {
                        $query->where('user_id', auth('api')->id());
                    }
                });
            })
            ->get();
    }

    public function managerInquiries($type) {
        $inquiries =  $this->model::query()
            ->where(function ($query) {

                // auth('manager')->user()->load(['roles'=>function($q){
                //     return $q->whereHas('permissions',function($q){
                //         return $q->where('name', 'inquiries-read');
                //     });
                // }]);
                if (auth('manager')->user()->hasRole('teacher') || (!auth('manager')->user()->hasRole(['super-admin', 'admin']) && !auth('manager')->user()->hasPermission('inquiries-read'))) {
                    $query->where('type', 'EDUCATIONAL');
                    $query->where(function ($query) {
                        $query->whereHas('course', function ($query) {
                            $query->whereHas('teachers', function ($query) {
                                $query->where('manager_id', auth('manager')->id());
                            });
                        });
                    });
                }
            })
            ->orderBy('created_at', 'desc');
            if (!is_null($type) && $type!='ALL'){
                if ($type == 0){
                    $inquiries->whereNull('answer');
                }elseif($type == 1){
                    $inquiries->whereNotNull('answer');
                }
            }
            if (request()->has('course') && request('course') != "ALL"){
                $inquiries->where('course_id' , request('course'));
            }
            if (request()->has('questions_type') && request('questions_type') != "ALL"){
                $inquiries->where('type' , request('questions_type'));
            }
            if (request()->has('is_public') && request('is_public') != "ALL"){
                $inquiries->where('is_public' , request('is_public'));
            }

            return $inquiries;

    }

    public function getManagerInquiries($type,$paginate=10)
    {
        $paginate=in_array($paginate,config('pagination.values'))?$paginate:config('pagination.default');
        return $this->managerInquiries($type)->paginate($paginate);
    }

    public function inquiriesCount() {
        $type=null;
        return $this->managerInquiries($type)->whereNull('answer')->orWhere('answer', '')->count();
    }
}


