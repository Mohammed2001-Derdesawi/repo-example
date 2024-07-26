<?php

namespace App\Http\Requests\Dashboard\Categories;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
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
            'is_active' => 'nullable',
            'image' => 'nullable|exclude|image|mimes:jpg,jpeg,png|max:2048',
            'field_id' => ['required' , Rule::exists('fields' , 'id')],
            'sort' => 'required|integer|gte:1',
        ];
    }
}
