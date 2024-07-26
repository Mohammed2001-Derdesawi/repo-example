<?php

namespace App\Providers;

use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        ################### Start Dashboard Gates ###################

        Gate::define('control-course', function ($user, $course) {
            return
                $user->hasRole(['super-admin', 'admin'])
                || $course?->whereHas('teachers', function ($query) {
                    $query->where('managers.id', auth()->id());
                })->exists() || $user->hasPermission('inquiries-update');
        });

        Gate::define('delete-standard', function ($user, $standard) {
            return $standard->lectures?->count() == 0;
        });

        Gate::define('delete-field', function ($user, $field) {
            return $field->categories?->count() == 0;
        });

        Gate::define('delete-lecture', function ($user, $lecture) {
            return true;
        });

        Gate::define('delete-category', function ($user, $category) {
            return $category->courses?->count() == 0;
        });

        Gate::define('delete-role', function ($user, $role) {
            return $role->users()?->count() == 0 && $role->is_deletable;
        });

        Gate::define('delete-course' , function ($user , $course){
           return !$course->subscriptions()?->exists();
        });

        Gate::define('delete-exam' , function ($user , $exam){
//            return !$exam->users()?->exists();
            return true;
        });

        Gate::define('edit-exam-type', function ($user, $exam) {
            return !$exam->questions()?->eixsts();
        });

        Gate::define('delete-book', function ($user, $book) {
            return !$book->users()?->exists();
        });

        Gate::define('confirm-payment', function ($user, $payment) {
            return !$payment->is_confirmed && !$payment->is_declined;
        });

        Gate::define('decline-payment', function ($user, $payment) {
            return !$payment->is_confirmed && !$payment->is_declined;
        });

        Gate::define('operate-inquiry', function ($user, $inquiry) {
            return
                $user->hasRole(['super-admin', 'admin'])
                || $user->hasPermission('inquiries-update')
                || ($user->courses()->where('course_id', $inquiry->course_id)->exists() && $inquiry->type == 'EDUCATIONAL');
        });

        ################### End Dashboard Gates ###################


        ################### Start Api Gates ###################

        Gate::define('start-exam', function ($user, $exam) {
            return
                ($user->courses()?->where('course_id', $exam?->course_id)?->exists()
                || $exam->is_free )
                && !$user->exams()?->where('exam_id', $exam?->id)?->where('is_ended', false)?->exists()
//                && $exam?->attempts > $user->exams()?->where('exam_id', $exam->id)->count()
                && $exam->is_active;
        });

        Gate::define('perform-exam', function ($user, $examUser) {
            return
                $examUser?->exists()
                && $examUser?->user_id == $user->id
                && !$examUser?->is_ended;
        });

        Gate::define('show-exam', function ($user, $examUser) {
            return
                $examUser?->exists()
                && $examUser?->user_id == $user->id
                && $examUser?->is_ended;
        });

        Gate::define('add-to-cart', function ($user, $cart, $itemId, $itemType) {
            return
                !$cart->items()?->where('cartable_id', $itemId)->where('cartable_type', $itemType)->exists()
                && !($itemType == Course::class && $user->courses()->where('course_id', $itemId)->exists());
        });

        Gate::define('remove-from-cart', function ($user, $cart, $cartItemId) {
            return $cart->items()?->where('id', $cartItemId)->exists();
        });

        Gate::define('cant_attend_lecture' , function ($user , $lecture){
            return
                (!$lecture->is_free  &&
                !$user->courses->contains('id', $lecture->standard->course_id))
                ||
                $user->watchedLectures->contains('lecture_id', $lecture->id);
        });

        Gate::define('request-certificate', function ($user, $course) {
//            $watchedLecturesCount = $user->watchedLectures()->whereHas('lecture', function ($query) use ($course) {
//                $query->whereHas('standard', function($query) use ($course) {
//                    $query->where('course_id', $course->id);
//                });
//            })->count();
//            return $course->certificate_price !== null && $watchedLecturesCount == $course->lectures()->count();

            return !$course->isRequestCertificate();
        });


        ################### End Api Gates ###################



    }
}
