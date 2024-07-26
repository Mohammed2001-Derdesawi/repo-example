<?php

namespace App\Http\Controllers\Dashboard\Structure;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Content\ContactUsRequest;
use Illuminate\Http\Request;

class ContactUsController extends ContentController
{
    protected string $contentKey = 'contact-us';
    protected array $locales = ['en', 'ar'];

    protected function store(ContactUsRequest $request){
        return parent::_store($request);
    }
}
