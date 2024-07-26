<?php

namespace App\Http\Controllers\Api\Structure;

use App\Http\Resources\Structure\Home\HomeResource;

class HomeController extends StructureController
{
    protected string $contentKey = 'home';
    protected $resource = HomeResource::class;
}
