<?php

namespace App\Http\Requests\Dashboard\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FieldRequest extends FormRequest
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
            'is_active' => 'nullable',
            'show_in_navbar' => 'nullable',
            'image' => 'nullable|exclude|image|mimes:jpg,jpeg,png|max:2048',
            'color_code' => 'required',
            'sort' => 'required|integer|gte:1',
            'common_questions' => 'array',
            'common_questions.*.title_ar' => 'sometimes|required',
            'common_questions.*.content_ar' => 'sometimes|required',
            'common_questions.*.title_en' => 'nullable',
            'common_questions.*.content_en' => 'nullable',
        ];

    }
}
