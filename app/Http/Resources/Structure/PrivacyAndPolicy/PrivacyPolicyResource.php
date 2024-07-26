<?php

namespace App\Http\Resources\Structure\PrivacyAndPolicy;

use Illuminate\Http\Resources\Json\JsonResource;

class PrivacyPolicyResource extends JsonResource
{

    public function toArray($request)
    {
        return [
          'title' => $this->title,
          'section1' => [
              'title' => $this->section1->title,
              'description' => $this->section1->description,
          ],
            'section2' => [
                'title' => $this->section2->title,
                'description' => $this->section2->description,
            ]
        ];
    }
}
