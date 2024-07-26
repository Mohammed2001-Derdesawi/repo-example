<?php

namespace App\Http\Controllers\Dashboard\Structure;

use App\Http\Requests\Dashboard\Content\HomeRequest;

class HomeController extends ContentController
{
    protected string $contentKey = 'home';
    protected array $locales = ['en', 'ar'];

    protected function store(HomeRequest $request){
        return parent::_store($request);
    }
}
