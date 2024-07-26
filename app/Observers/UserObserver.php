<?php

namespace App\Observers;

use App\Models\Course;
use App\Models\CourseUser;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
//         $courses = Course::query()->select('id')->inRandomOrder()->limit(3)->get()->pluck('id')->toArray();
// //        foreach ($courses as $course) {
// //            CourseUser::query()->create([
// //                'user_id' => $user->id,
// //                'course_id' => $course,
// //                'is_active' => true,
// //            ]);
// //        }
//         $user->courses()->attach($courses, ['is_active' => true]);
//         $user->save();
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
