<?php

namespace App\Http\Resources\Structure\AboutUs;

use App\Http\Resources\Structure\Home\SectionThreeFeaturesResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AboutUsResource extends JsonResource
{
    private mixed $appends;
    public function __construct($resource, $withSections)
    {
        parent::__construct($resource);
        $this->appends = $withSections;
    }

    public function toArray($request)
    {
        return [
            'section1' => [
                'title' => $this->section1->title,
                'description' => $this->section1->description,
                'image' => $this->section1->image,
            ],
            'section2' => [
                'title' => $this->section2->title,
                'partners' => array_values((array)$this->section2->partners)
            ],
            'section3' => [
                'title' => $this->appends['home']['section3']->title,
                'description' => $this->appends['home']['section3']->description,
                'features' => SectionThreeFeaturesResource::collection($this->appends['home']['section3']->features),
            ],
        ];
    }
}
