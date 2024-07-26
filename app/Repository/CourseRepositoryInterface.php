<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface CourseRepositoryInterface extends RepositoryInterface
{
    public function getAllWhereHasStandards();

    public function filterCourses($perPage = 10 , $columns = ['*']);

    public function getUserCoursesByProgress($user_id , $type = "PROGRESS");

    public function getRequestableCertificates();

    public function getImportantCourses();

    public function getUnSubscribedStudentCourses($student_id);

    public function isExisted($course_id);

    public function getUnSubscribesCourseExams($id);

    public function canBeRegistered($id);

    public function getActiveById($modelId, array $columns = ['*'], array $relations = [], array $appends = []): ?Model;
    public function getCourseFromCoursesByPrice($courses,$ord='ASC');
    public function isUserSubscribed($student_id,$course_id,$columns=['*']);
}
