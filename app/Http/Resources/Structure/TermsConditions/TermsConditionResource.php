<?php

namespace App\Http\Resources\Structure\TermsConditions;

use App\Http\Resources\Structure\Home\SectionThreeFeaturesResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TermsConditionResource extends JsonResource
{
    private mixed $section3;
    public function __construct($resource, $section3)
    {
        parent::__construct($resource);
        $this->section3 = $section3;
    }

    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'text' => $this->text,
        ];
    }
}
