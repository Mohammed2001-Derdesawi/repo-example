<?php

namespace App\Http\Requests\Api\Payment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConfirmPaymentRequest extends FormRequest
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
            'payment_id' => [
                'required',
                Rule::exists('payments', 'id')
                    ->where('is_confirmed', '0')
                    ->where('user_id', auth('api')->id())
            ],
        ];
    }
}
