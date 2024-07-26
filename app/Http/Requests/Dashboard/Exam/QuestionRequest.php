<?php

namespace App\Http\Requests\Dashboard\Exam;

use App\Rules\ArrayKeyPresent;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

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
            'question.standard_id' => 'sometimes|required_without:indicator_id|exists:standards,id',
            'question.indicator_id' => 'sometimes|required_without:standard_id|exists:indicators,id',
            'question.content' => 'required',
            'answers' => ['required', 'array', 'min:2', new ArrayKeyPresent('is_correct')],
            'answers.*.is_correct' => 'sometimes',
            'answers.*.content' => 'required',
            'answers.*.comment' => 'nullable',
            'question.is_active' => 'nullable',
            'order'=>'required|integer|max:600',
        ];
    }
}
