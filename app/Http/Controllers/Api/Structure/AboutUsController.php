<?php

namespace App\Http\Controllers\Api\Structure;

use App\Http\Resources\Structure\AboutUs\AboutUsResource;

class AboutUsController extends StructureController
{
    protected string $contentKey = 'about-us';
    protected $resource = AboutUsResource::class;
    protected array $with = [
        'home' => ['section3'],
    ];
}
