<?php

namespace App\Http\Requests\Dashboard\Exam;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExamRequest extends FormRequest
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
            'type' => [($this->isMethod('POST') ? 'required' : '') , Rule::in(['COURSE' , 'STANDARD' , 'LECTURE'])],
            'duration' => 'required|min:0.1',
            'course_id' => [($this->isMethod('POST') ? 'required' : '') , Rule::exists('courses' , 'id')],
            'standard_id' => [Rule::requiredIf($this->type =="STANDARD" OR $this->type == 'LECTURE') , Rule::exists('standards' , 'id')],
            'lecture_id' => [Rule::requiredIf($this->type =="LECTURE") , Rule::exists('lectures' , 'id')],
            'attempts' => 'nullable|integer|min:1',
            'solution_video_link' => 'nullable',
            'solution_video_platform' => 'required_with:solution_video_link',

        ];

    }
}
