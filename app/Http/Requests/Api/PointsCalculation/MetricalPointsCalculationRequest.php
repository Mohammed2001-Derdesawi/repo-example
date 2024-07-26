<?php

namespace App\Http\Requests\Api\PointsCalculation;

use Illuminate\Foundation\Http\FormRequest;

class MetricalPointsCalculationRequest extends FormRequest
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
            'high_school_percentage' => 'required|numeric|between:0,100',
            'high_school_percentage_rate' => 'required|numeric|between:0,100',
            'aptitude_exam_mark' => 'required|numeric|gte:0',
            'aptitude_exam_percentage_rate' => 'required|numeric|between:0,100',
            'achievement_exam_mark' => 'required|numeric|gte:0',
            'achievement_exam_percentage_rate' => 'required|numeric|between:0,100'
        ];
    }
}
