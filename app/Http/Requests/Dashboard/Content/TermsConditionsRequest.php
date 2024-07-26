<?php

namespace App\Http\Requests\Dashboard\Content;

use Illuminate\Foundation\Http\FormRequest;

class TermsConditionsRequest extends FormRequest
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
            'ar.text' => 'required',
            'en.title' => 'required',
            'en.text' => 'required',
        ];
    }
}
