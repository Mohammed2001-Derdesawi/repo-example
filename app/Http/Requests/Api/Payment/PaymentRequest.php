<?php

namespace App\Http\Requests\Api\Payment;

use App\Models\CourseBook;
use App\Repository\CartRepositoryInterface;
use App\Repository\CertificateUserRepositoryInterface;
use App\Rules\Phone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentRequest extends FormRequest
{
    private CartRepositoryInterface $cartRepository;
    private CertificateUserRepositoryInterface $certificateUserRepository;

    public function __construct(
        CartRepositoryInterface $cartRepository,
        CertificateUserRepositoryInterface $certificateUserRepository,
        array $query = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        $content = null
    )
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
        $this->cartRepository = $cartRepository;
        $this->certificateUserRepository = $certificateUserRepository;
    }

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
        $payable = [
            'cart_id' => ['required', function($attribute, $value, $fail) {
                $isPayable = $this->cartRepository->isPayable($this->input('cart_id'));
                if (!$isPayable) {
                    $fail(__('messages.Cart is empty'));
                }
            }],
            'certificate_user_id' => ['required', Rule::exists('certificate_user', 'id')->where('user_id', auth('api')->id())],
        ];

        $rules = [
            'type' => ['required', 'in:CASH,EPAYMENT,TAMARA'],
            'pay' => 'required|in:cart,certificate_user',
            'city' => 'nullable',
            $this->input('pay').'_id' => $payable[$this->input('pay').'_id'],
        ];

        if ($this->input('cart_id') !== null) {
            $cart = $this->cartRepository->getById($this->input('cart_id'));
            if ($cart?->items()?->where('cartable_type', CourseBook::class)->where('option', 'PRINT')->exists()) {
                $rules['name'] = 'required';
                $rules['email'] = 'required|email:rfc';
                $rules['phone'] = ['required', new Phone];
//                $rules['nationality'] = 'required';
//                $rules['national_id'] = 'required';
                $rules['address'] = 'required';
                $rules['city'] = 'required|string';
            }
        }

        if ($this->input('certificate_user_id') !== null) {
            $rules['name'] = 'required';
            $rules['email'] = 'required|email:rfc,dns';
            $rules['phone'] = ['required', new Phone];
            $rules['nationality'] = 'required';
            $rules['national_id'] = 'required';
            $rules['qualification'] = 'required';
//            $rules['address'] = 'required';
        }
        if ($this->input('type') == 'CASH') {
            $rules['transfer_image'] = 'required|exclude|file|mimes:jpg,jpeg,png|max:512';
            $rules['bank_account_name'] = 'required|max:255';
            $rules['bank_account_number'] = 'required|max:255';
            $rules['from_bank'] = 'required|max:255';
            $rules['to_bank'] = 'required|max:255';
            $rules['transfer_amount'] = 'required';
            $rules['transfer_date'] = 'required|max:255';
            $rules['transfer_time'] = 'required|max:255';
        } elseif($this->input('type') == 'EPAYMENT') {
            if( (isset($cart) && ($cart->total_amount!=0)) || $this->input('certificate_user_id')!=null )
            $rules['token'] = [Rule::requiredIf(request()->is('api/w/*') || request()->is('w/*')), 'exclude'];
        } elseif ($this->input('type') == 'TAMARA') {
            $rules['instalments'] = [Rule::requiredIf(request()->is('api/w/*') || request()->is('w/*')), 'exclude', Rule::in([2, 3, 4])];
        }

        return $rules;
    }
}
