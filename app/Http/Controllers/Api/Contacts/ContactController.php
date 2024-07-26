<?php

namespace App\Http\Controllers\Api\Contacts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Contacts\SendContactRequest;
use App\Http\Services\Api\Contact\ContactService;
use App\Http\Traits\Responser;
use App\Repository\ContactRepositoryInterface;

class ContactController extends Controller
{
   private ContactService $contactService;

    public function __construct(ContactService $contactService){
        $this->contactService = $contactService;

    }

    public function __invoke(SendContactRequest $request)
    {
        return $this->contactService->sendContact($request);
    }
}
