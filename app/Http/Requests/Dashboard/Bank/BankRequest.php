<?php

namespace App\Http\Requests\Dashboard\Bank;

use Illuminate\Foundation\Http\FormRequest;

class BankRequest extends FormRequest
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
            'name_ar' => 'required|max:255',
            'name_en' => 'nullable|max:255',
            'image' => 'nullable',
            'account_number' => 'required',
            'iban_number' => 'required',
            'account_name_ar' => 'required|max:255',
            'account_name_en' => 'nullable|max:255'
        ];
    }
}
