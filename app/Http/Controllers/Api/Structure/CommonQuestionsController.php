<?php

namespace App\Http\Controllers\Api\Structure;

use App\Http\Resources\Structure\CommonQuestions\CommonQuestionResource;

class CommonQuestionsController extends StructureController
{
    protected string $contentKey = 'common_questions';
    protected $resource = CommonQuestionResource::class;


}
