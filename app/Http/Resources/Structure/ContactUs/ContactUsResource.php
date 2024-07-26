<?php

namespace App\Http\Resources\Structure\ContactUs;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactUsResource extends JsonResource
{

    public function toArray($request)
    {
        return [
          'title' => $this->title,
          'section1' => [
              'title' => $this->section1->title,
              'description' => $this->section1->description
          ],
          'section2' => $this->section2

        ];
    }
}
