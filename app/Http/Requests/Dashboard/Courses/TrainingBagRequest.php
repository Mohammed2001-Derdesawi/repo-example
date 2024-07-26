<?php

namespace App\Http\Requests\Dashboard\Courses;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TrainingBagRequest extends FormRequest
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
            'description_ar' => 'nullable',
            'description_en' => 'nullable',
            'course_id' => 'required',
            'price' => 'requiredIf:is_printable,on',
            'image' => 'nullable',
            'is_printable' => 'nullable',
        ];
    }
}
