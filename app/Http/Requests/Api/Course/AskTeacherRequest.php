<?php

namespace App\Http\Requests\Api\Course;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AskTeacherRequest extends FormRequest
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
            'question' => 'required',
            'image' => 'nullable',
            'voice' => 'nullable',
            'type' => ['required' , Rule::in('EDUCATIONAL' , 'TECHNICAL')]
        ];
    }
}
