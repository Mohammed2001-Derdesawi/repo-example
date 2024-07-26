<?php

namespace App\Http\Services\Dashboard\Subscriptions;

use App\Http\Requests\Dashboard\Coupons\CouponRequest;
use App\Models\Course;
use App\Models\CourseBook;
use App\Repository\CouponRepositoryInterface;
use App\Repository\CourseSubscriptionRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SubscriptionService
{
    private CourseSubscriptionRepositoryInterface $courseSubscriptionRepository;

    public function __construct(CourseSubscriptionRepositoryInterface $courseSubscriptionRepository)
    {
        $this->courseSubscriptionRepository = $courseSubscriptionRepository;
    }

    public function toggleActivate($id)
    {
        $subscription = $this->courseSubscriptionRepository->getById($id);
        try
        {
            DB::beginTransaction();
            $oldcourse = Course::find($subscription->course_id);
            $end_subscribe = null;
            if($oldcourse->dayNumbers)
            {
                $enddate = Carbon::parse(now())->addDays($oldcourse->dayNumbers);
                $end_subscribe = $enddate->toDateString();
            }
            $subscription->update(['is_active' => !$subscription->is_active , 'end_subscribe' => $end_subscribe]);
            DB::commit();
            return back()->with('success' , __('messages.updated successfully'));
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return back()->with('error' , __('messages.Something went wrong'));
        }
    }

    public function addTrial($request){
        try {
            DB::beginTransaction();
            $this->courseSubscriptionRepository->create([
                'user_id' => $request['student_id'],
                'course_id' => $request['course_id'],
                'is_active' => 'on'
            ]);
            DB::commit();
            return back()->with('success' , __('messages.Subscription Added Successfully'));
        }catch (\Exception $e){
            DB::rollBack();
            return back()->with('error' , __('messages.Something went wrong'));
        }
    }

    public function delete($id){
        try {
            DB::beginTransaction();
            $this->courseSubscriptionRepository->delete($id);
            DB::commit();
            return back()->with('success' , __('messages.Subscription Deleted Successfully'));
        }catch (\Exception $e){
            DB::rollBack();
            return back()->with('error' , __('messages.Something went wrong'));
        }
    }

}
