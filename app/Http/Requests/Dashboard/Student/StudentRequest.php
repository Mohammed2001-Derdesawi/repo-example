<?php

namespace App\Http\Requests\Dashboard\Student;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
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
            'email' => 'required|email:rfc,dns|unique:users,email' . ($this->isMethod('PUT') ? ',' . $this->student : ''),
            'phone' => 'required|min:8|unique:users,phone' . ($this->isMethod('PUT') ? ',' . $this->student : ''),
            'password' => ($this->isMethod('POST') ? 'required|' : 'nullable|') . 'min:8|confirmed',
            'address' => 'array|exclude',
            'is_active' => 'nullable'
        ];
    }
}
