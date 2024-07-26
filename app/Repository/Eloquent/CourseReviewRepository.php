<?php

namespace App\Repository\Eloquent;

use App\Models\Answer;
use App\Models\Review;
use App\Repository\AnswerRepositoryInterface;
use App\Repository\CourseReviewRepositoryInterface;
use App\Repository\QuestionRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class CourseReviewRepository extends Repository implements CourseReviewRepositoryInterface
{
    protected Model $model;

    public function __construct(Review $model){
        parent::__construct($model);
    }

}
