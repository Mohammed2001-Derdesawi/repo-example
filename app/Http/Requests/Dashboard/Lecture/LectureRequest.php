<?php

namespace App\Http\Requests\Dashboard\Lecture;

use Illuminate\Foundation\Http\FormRequest;

class LectureRequest extends FormRequest
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
            'type' => 'required|in:RECORDED,LIVE',
            'standard_id' => 'required|exists:standards,id',
            'name_ar' => 'required',
            'name_en' => 'nullable',
            'description_ar' => 'required',
            'description_en' => 'nullable',
            'record_link' => 'nullable',
            'link_platform' => 'nullable',
            'publish_at' => 'required_with:record_link|exclude_without:record_link',
            'live_link' => 'nullable',
            'starts_at' => 'required_with:live_link|exclude_without:live_link',
            'ends_at' => 'required_with:live_link|exclude_without:live_link',
            'duration' => 'nullable|min:0.1',
            'sort' => 'required|integer|gte:1',
            'is_active' => 'nullable',
            'is_free' => 'nullable',
            'pins.*.time' => 'sometimes|required',
            'pins.*.name_ar' => 'sometimes|required',
            'pins.*.name_en' => 'nullable',
            'indicators.*.name_ar' => 'sometimes|required',
            'indicators.*.name_en' => 'nullable',
        ];
    }
}
