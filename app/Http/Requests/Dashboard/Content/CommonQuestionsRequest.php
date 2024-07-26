<?php

namespace App\Http\Requests\Dashboard\Content;

use Illuminate\Foundation\Http\FormRequest;

class CommonQuestionsRequest extends FormRequest
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
            'en.title' => 'required|nullable:255',
            'en.questions.*.question' => 'nullable',
            'en.questions.*.answer' => 'nullable',
            'ar.title' => 'required|max:255',
            'ar.questions.*.question' => 'required',
            'ar.questions.*.answer' => 'required',

        ];
    }
}
