<?php

namespace App\Http\Requests\Dashboard\Coupons;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CouponRequest extends FormRequest
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
            'type'=>'required|in:ALL,SPECIAL,SUBSCRIBE',
            'coupon' => ['required','max:255' , Rule::unique('coupons' , 'coupon')->ignore($this->route('coupon'))],
            'discount' => 'required|numeric|min:1|max:100',
            'max_uses' => 'nullable|numeric|min:1',
            'couponable_id'=>[Rule::requiredIf(function(){
                return $this->type=='SPECIAL';
            })],
            'couponable_type' =>[Rule::requiredIf(function(){
                return $this->type=='SPECIAL';
            })],
            'is_active' => 'nullable',
            'mobile_only' => 'nullable',
            'basic_subscribe_course'=>'nullable|exists:courses,id',
            'subscribe_courses'=>['nullable','array',function ($attribute, $value, $fail){
                $basicCourse = $this->input('basic_subscribe_course', []);
                if (in_array($basicCourse,$value)) {
                    $fail(__('validation.basic_cousre_not_in_subscribers'));
                }
            }],
            'subscribe_courses.*'=>['nullable','exists:courses,id'],
            'auto_discount'=>'nullable'
        ];
    }
}
