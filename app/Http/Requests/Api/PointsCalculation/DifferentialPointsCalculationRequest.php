<?php

namespace App\Http\Requests\Api\PointsCalculation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DifferentialPointsCalculationRequest extends FormRequest
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
            'average_rate' => ['required', Rule::in([4, 5, 100])],
            'average' => ['required', 'numeric', 'between:0,'.$this->input('average_rate')],
            'specialization_exam_mark' => 'required|numeric|between:0,100',
            'general_exam_mark' => 'required|numeric|between:0,100',
            'year' => 'required|integer',
            'month' => 'required|integer',
            'day' => 'required|integer',
            'deadlineday' => 'required|integer',
            'deadlineyear' => 'required|integer',
            'deadlinemonth' => 'required|integer',

        ];
    }
}
