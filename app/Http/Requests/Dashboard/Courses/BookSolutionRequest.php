<?php

namespace App\Http\Requests\Dashboard\Courses;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookSolutionRequest extends FormRequest
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
           'name_ar' => 'required|max:255',
           'name_en' => 'nullable|max:255',
           'solution_video_link' => 'required',
           'course_id' => ['required' , Rule::exists('courses' , 'id')]
        ];
    }
}
