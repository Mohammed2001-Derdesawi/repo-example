<?php

namespace App\Http\Requests\Dashboard\PrintRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PrintRequestRequest extends FormRequest
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
            'status' => ['required' , Rule::in(['ORDERED' , 'APPROVED' , 'DELIVERED' , 'CANCELED'])]
        ];
    }
}
