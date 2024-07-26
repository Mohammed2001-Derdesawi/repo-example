<?php

namespace App\Http\Requests\Dashboard\Content;

use Illuminate\Foundation\Http\FormRequest;

class HomeRequest extends FormRequest
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
            'en.section1.title' => 'nullable|max:255',
            'en.section1.description' => 'nullable|max:255',
            'en.section1.button' => 'nullable|max:255',
            'en.section1.image' => 'nullable',
            'en.section2.title' => 'nullable|max:255',
            'en.section2.more' => 'nullable|max:255',
            'en.section3.title' => 'nullable|max:255',
            'en.section3.description' => 'nullable|max:255',
            'en.section3.feature.*.image' => 'nullable',
            'en.section3.feature.*.title' => 'nullable|max:255',
            'en.section3.feature.*.description' => 'nullable|max:255',
            'en.section4.title' => 'nullable|max:255',
            'en.section5.title' => 'nullable|max:255',
            'en.section5.images.*' => 'nullable',
            'en.section6.title' => 'nullable|max:255',
            'all.section6.accounts.*.account' => 'required|max:255',
//            // ar
            'ar.section1.title' => 'required|max:255',
            'ar.section1.description' => 'required|max:255',
            'ar.section1.button' => 'required|max:255',
            'ar.section1.image' => 'nullable',
            'ar.section2.title' => 'required|max:255',
            'ar.section2.more' => 'required|max:255',
            'ar.section3.title' => 'required|max:255',
            'ar.section3.description' => 'required|max:255',
            'ar.section3.feature.*.image' => 'nullable',
            'ar.section3.feature.*.title' => 'required|max:255',
            'ar.section3.feature.*.description' => 'required|max:255',
            'ar.section4.title' => 'required|max:255',
            'ar.section5.title' => 'required|max:255',
            'ar.section5.images.*' => 'nullable',
            'ar.section6.title' => 'required|max:255',
        ];
    }
}
