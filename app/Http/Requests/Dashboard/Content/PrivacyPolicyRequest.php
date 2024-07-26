<?php

namespace App\Http\Requests\Dashboard\Content;

use Illuminate\Foundation\Http\FormRequest;

class PrivacyPolicyRequest extends FormRequest
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
            'en.title' => 'nullable|max:255',
            'en.section1.title' => 'nullable|max:255',
            'en.section1.description' => 'required',
            'en.section2.title' => 'nullable|max:255',
            'en.section2.description' => 'required',
            'ar.title' => 'required|max:255',
            'ar.section1.title' => 'required|max:255',
            'ar.section1.description' => 'required',
            'ar.section2.title' => 'required|max:255',
            'ar.section2.description' => 'required',
        ];
    }
}
