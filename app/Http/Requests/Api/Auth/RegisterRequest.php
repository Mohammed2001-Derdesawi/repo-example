<?php

namespace App\Http\Requests\Api\Auth;

use App\Rules\Phone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
            'name' => 'required|max:255',
            'phone' => ['required', new Phone, 'min:8', Rule::unique('users', 'phone')],
            'email' => ['required', 'email:rfc,dns', Rule::unique('users' , 'email')],
//            'password' => Password::min(8)->numbers()->symbols()->letters(),
            'password' => Password::min(8),
            'fcm_token' => 'nullable',
//            'gender' => ['required' , Rule::in(['MALE' , 'FEMALE'])]
        ];
    }
}
