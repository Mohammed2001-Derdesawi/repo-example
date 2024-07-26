<?php

namespace App\Repository\Eloquent;

use App\Models\CourseUser;
use App\Repository\CourseUserRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class CourseUserRepository extends Repository implements CourseUserRepositoryInterface
{
    protected Model $model;

    public function __construct(CourseUser $model)
    {
        parent::__construct($model);
    }
}
