<?php

namespace App\Http\Controllers\Api\Structure;

use App\Http\Resources\Structure\TermsConditions\TermsConditionResource;

class TermsConditionsController extends StructureController
{
    protected string $contentKey = 'terms-and-conditions';
    protected $resource = TermsConditionResource::class;
}
