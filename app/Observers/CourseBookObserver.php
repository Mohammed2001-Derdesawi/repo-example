<?php

namespace App\Observers;

use App\Models\CourseBook;

class CourseBookObserver
{
    /**
     * Handle the CourseBook "deleted" event.
     *
     * @param  \App\Models\CourseBook  $courseBook
     * @return void
     */
    public function deleted(CourseBook $courseBook)
    {
        if ($courseBook->cart()->exists())
            $courseBook->cart()->delete();
        if ($courseBook->parts()->exists())
            $courseBook->parts()->delete();
    }
}
