<?php

namespace App\Http\Controllers\Dashboard\Structure;

use App\Http\Requests\Dashboard\Content\TermsConditionsRequest;

class TermsConditionsController extends ContentController
{
    protected string $contentKey = 'terms-and-conditions';
    protected array $locales = ['en', 'ar'];

    protected function store(TermsConditionsRequest $request){
        return parent::_store($request);
    }
}
