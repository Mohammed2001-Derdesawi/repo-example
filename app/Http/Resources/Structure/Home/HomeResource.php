<?php

namespace App\Http\Resources\Structure\Home;

use App\Http\Resources\Course\CourseResource;
use App\Http\Resources\Field\Web\FieldResource;
use App\Repository\CourseRepositoryInterface;
use App\Repository\FieldRepositoryInterface;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeResource extends JsonResource
{


    private FieldRepositoryInterface $fieldRepository;
    private CourseRepositoryInterface $courseRepository;


    public function toArray($request)
    {
        $this->fieldRepository = app(FieldRepositoryInterface::class);
        $this->courseRepository = app(CourseRepositoryInterface::class);

        $fields = $this->fieldRepository->getActive();
        $courses = $this->courseRepository->getImportantCourses();
        return [
            'section1' => $this->section1,
            'section2' => [
                'content' => $this->section2,
                'courses' => CourseResource::collection($courses)
            ],
            'section3' => [
                'title' => $this->section3->title,
                'description' => $this->section3->description,
                'features' => SectionThreeFeaturesResource::collection($this->section3->features),
            ],
            'section4' => [
                'content' => $this->section4,
                'fields' => FieldResource::collection($fields)
            ],
            'section5' => [
                'title' => $this->section5->title,
                'images' => SectionFiveImagesResource::collection(array_values((array) $this->section5->images)),
            ],
            'section6' => [
                'title' => $this->section6->title,
                'accounts' => SectionSixAccountsResource::collection(array_values((array) $this->section6->accounts)),
            ]
        ];
    }
}
