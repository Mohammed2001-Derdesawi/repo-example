<?php

namespace App\Http\Middleware;

use App\Http\Traits\Responser;
use Closure;
use Illuminate\Http\Request;

class UserSubscribedToCourse
{
    use Responser;

    public function handle(Request $request, Closure $next)
    {
        $subscription = $request->user()->subscriptions()->where('course_id' , $request->id)->first();
        if ($subscription !== null) {
            if ($subscription->is_active){
                return $next($request);
            }
            return $this->responseFail(403 , __('messages.This course is not activated for you Contact with platform management'));
        }
        return $this->responseFail(401 , __('messages.You are not authorized to this course'));
    }
}
//
