<?php

namespace App\Http\Requests\Dashboard\Managers;

use Illuminate\Foundation\Http\FormRequest;

class ManagerRequest extends FormRequest
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
            'image' => 'nullable|exclude|image|mimes:jpg,jpeg,png|max:2048',
            'name' => 'required',
            'email' => 'required|email:rfc,dns|unique:managers,email' . ($this->isMethod('PUT') ? ',' . $this->manager : ''),
            'phone' => 'required|min:8|unique:managers,phone' . ($this->isMethod('PUT') ? ',' . $this->manager : ''),
            'password' => ($this->isMethod('POST') ? 'required|' : 'nullable|') . 'min:8|confirmed',
            'gender' => 'required|in:MALE,FEMALE',
            'birth_date' => 'required|before:today',
            'cv_pdf' => 'nullable|exclude|image|mimes:pdf,doc,docx|max:2048',
            'cv_description' => 'nullable'
        ];
    }
}
