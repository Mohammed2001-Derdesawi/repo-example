<?php

namespace App\Http\Requests\Dashboard\Courses;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttachmentRequest extends FormRequest
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
            'attachments.0.file' => 'mimes:pdf,jpg,jpeg,png,gif|' . ($this->isMethod('POST') ? 'required' : ''),
            'attachments.0.name_ar' => 'required|max:255',
            'attachments.0.name_en' => 'nullable|max:255',
            'attachments.0.is_active' => 'nullable',
        ];
    }
}
