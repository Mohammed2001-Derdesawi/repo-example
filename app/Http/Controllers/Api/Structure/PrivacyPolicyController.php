<?php

namespace App\Http\Controllers\Api\Structure;

use App\Http\Resources\Structure\PrivacyAndPolicy\PrivacyPolicyResource;

class PrivacyPolicyController extends StructureController
{
    protected string $contentKey = 'privacy_and_policy';
    protected $resource = PrivacyPolicyResource::class;


}
