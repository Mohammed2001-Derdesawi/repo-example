<?php

namespace App\Http\Requests\Dashboard\Content;

use Illuminate\Foundation\Http\FormRequest;

class ContactUsRequest extends FormRequest
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
            'ar.title' => 'required',
            'en.title' => 'nullable',
            'ar.section1.title' => 'required',
            'en.section1.title' => 'nullable',
            'ar.section1.description' => 'required',
            'en.section1.description' => 'nullable',
            'all.section2.*.icon' => 'required',
            'ar.section2.*.title' => 'required',
            'en.section2.*.title' => 'nullable',
            'ar.section2.*.description' => 'required',
            'en.section2.*.description' => 'nullable',
        ];
    }
}
