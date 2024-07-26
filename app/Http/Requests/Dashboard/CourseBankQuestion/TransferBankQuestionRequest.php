<?php

namespace App\Http\Requests\Dashboard\CourseBankQuestion;

use App\Rules\ArrayKeyPresent;
use Illuminate\Foundation\Http\FormRequest;

class TransferBankQuestionRequest extends FormRequest
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
            'base_course_id'=>'required|exists:courses,id',
            'to_course_id'=>'required|exists:courses,id',
        ];
    }
}
