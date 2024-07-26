<?php

namespace App\Http\Requests\Dashboard\CourseBankQuestion;

use App\Rules\ArrayKeyPresent;
use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
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
            'content' => 'required',
            'answers' => ['required', 'array', 'min:2', new ArrayKeyPresent('is_correct')],
            'answers.*.is_correct' => 'sometimes',
            'answers.*.content' => 'required',
            'answers.*.comment' => 'nullable',
            'is_active' => 'nullable',
        ];
    }
}
