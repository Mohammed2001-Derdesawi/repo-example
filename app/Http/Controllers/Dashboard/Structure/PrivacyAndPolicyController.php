<?php

namespace App\Http\Controllers\Dashboard\Structure;

use App\Http\Requests\Dashboard\Content\PrivacyPolicyRequest;
use Illuminate\Http\Request;

class PrivacyAndPolicyController extends ContentController
{

    protected string $contentKey = 'privacy_and_policy';
    protected array $locales = ['en', 'ar'];

    protected function store(PrivacyPolicyRequest $request){
        return parent::_store($request);
    }
}
