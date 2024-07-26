<?php

namespace App\Repository;

interface ExamRepositoryInterface extends RepositoryInterface
{

    public function getCourseExams($id);

    public function getCount();
    public function getUserExams($user_id);
}
