<?php

namespace App\Http\Controllers\Dashboard\Structure;

use App\Http\Requests\Dashboard\Content\AboutUsRequest;

class AboutUsController extends ContentController
{
    protected string $contentKey = 'about-us';
    protected array $locales = ['en', 'ar'];

    protected function store(AboutUsRequest $request){
        return parent::_store($request);
    }
}
