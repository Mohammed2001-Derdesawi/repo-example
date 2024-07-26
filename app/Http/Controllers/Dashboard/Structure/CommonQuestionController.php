<?php

namespace App\Http\Controllers\Dashboard\Structure;

use App\Http\Requests\Dashboard\Content\CommonQuestionsRequest;

class CommonQuestionController extends ContentController
{
    protected string $contentKey = 'common_questions';
    protected array $locales = ['en', 'ar'];


    protected function store(CommonQuestionsRequest $request){
        return parent::_store($request);
    }
}
