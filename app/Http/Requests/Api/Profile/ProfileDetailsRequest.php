<?php

namespace App\Http\Requests\Api\Profile;

use App\Rules\Phone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileDetailsRequest extends FormRequest
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
            'image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'name' => 'required|string|min:3',
            'email' => ['required', 'email:rfc,dns', Rule::unique('users', 'email')->ignore($this->user('api')->id)],
            'phone' => ['required', new Phone, 'min:8', Rule::unique('users', 'phone')->ignore($this->user('api')->id)],
        ];
    }
}
