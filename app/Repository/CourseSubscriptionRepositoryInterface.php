<?php

namespace App\Repository;


interface CourseSubscriptionRepositoryInterface extends RepositoryInterface
{

    public function getByUserId($user_id , $perPage = 9);
    public function getByCourseId($course_id , $perPage = 9);


}
