<?php

namespace App\Http\Requests\Api\Exam;

use Illuminate\Foundation\Http\FormRequest;

class EndExamUserRequest extends FormRequest
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
            'user_exam_id' => 'required|exists:exam_user,id',
            'user_answers' => 'array',
            'user_answers.*.question_id' => 'required|exists:answer_user,id',
            'user_answers.*.answer_id' => 'nullable|exists:answers,id',
        ];
    }
}
