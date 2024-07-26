<?php

namespace App\Http\Requests\Dashboard\CourseBankQuestion;

use Illuminate\Foundation\Http\FormRequest;

class ExamBankQuestionsRequest extends FormRequest
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
            'standard_id' => 'sometimes|required_without:indicator_id|exists:standards,id',
            'indicator_id' => 'sometimes|required_without:standard_id|exists:indicators,id',
            'questions' => 'required',
        ];
    }
}
