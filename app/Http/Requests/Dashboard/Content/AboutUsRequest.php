<?php

namespace App\Http\Requests\Dashboard\Content;

use Illuminate\Foundation\Http\FormRequest;

class AboutUsRequest extends FormRequest
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
            'ar.section1.title' => 'required',
            'en.section1.title' => 'nullable',
            'ar.section1.description' => 'required',
            'en.section1.description' => 'nullable',
            'file.*' => 'sometimes|required'
        ];
    }
}
