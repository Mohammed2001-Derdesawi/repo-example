<?php

namespace App\Http\Controllers\Api\Structure;

use App\Http\Resources\Structure\ContactUs\ContactUsResource;

class ContactUsController extends StructureController
{
    protected string $contentKey = 'contact-us';
    protected $resource = ContactUsResource::class;
}
