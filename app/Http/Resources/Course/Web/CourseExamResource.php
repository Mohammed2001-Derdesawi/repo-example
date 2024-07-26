<?php

namespace App\Http\Resources\Course\Web;

use App\Http\Resources\Course\CourseExamsByTypeResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseExamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $isMobile = strpos($request->url(), 'test-url') !== false;
        return [
            'type' =>($isMobile)?__('dashboard.'.strtolower($this['type']).'_exams'):$this['type'],
            'exams' => CourseExamsByTypeResource::collection($this['exams']),
        ];
    }
}
