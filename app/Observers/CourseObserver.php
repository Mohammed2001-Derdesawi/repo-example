<?php

namespace App\Observers;

use App\Models\Course;
use App\Repository\StandardRepositoryInterface;

class CourseObserver
{
    private StandardRepositoryInterface $standardRepository;

    public function __construct(
        StandardRepositoryInterface $standardRepository
    )
    {
        $this->standardRepository = $standardRepository;
    }

    /**
     * Handle the User "created" event.
     */
    public function created(Course $course): void
    {
        $this->standardRepository->create([
            'name_en' => '',
            'name_ar' => '',
            'course_id' => $course->id,
        ]);
    }

    /**
     * Handle the Course "deleted" event.
     *
     * @param  \App\Models\Course  $course
     * @return void
     */
    public function deleted(Course $course)
    {
        if ($course->cart()->exists())
            $course->cart()->delete();
    }
}
