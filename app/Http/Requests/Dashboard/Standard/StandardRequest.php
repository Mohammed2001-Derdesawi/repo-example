<?php

namespace App\Http\Requests\Dashboard\Standard;

use Illuminate\Foundation\Http\FormRequest;

class StandardRequest extends FormRequest
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
            'name_ar' => 'required',
            'name_en' => 'nullable',
            'course_id' => 'required|exists:courses,id',
            'sort' => 'required|integer|gte:1'
        ];
    }
}
