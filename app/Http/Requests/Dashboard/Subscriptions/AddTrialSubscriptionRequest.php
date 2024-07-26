<?php

namespace App\Http\Requests\Dashboard\Subscriptions;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddTrialSubscriptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'student_id' => ['required' , Rule::exists('users' , 'id')],
            'course_id' => ['required' , Rule::exists('courses' , 'id')],
        ];
    }
}
