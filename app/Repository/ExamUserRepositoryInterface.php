<?php

namespace App\Repository;

interface ExamUserRepositoryInterface extends RepositoryInterface
{
    public function getMine($exam_id);
}
