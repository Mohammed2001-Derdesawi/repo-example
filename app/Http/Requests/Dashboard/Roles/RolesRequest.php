<?php

namespace App\Http\Requests\Dashboard\Roles;

use Illuminate\Foundation\Http\FormRequest;

class RolesRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'display_name_ar' => 'required|max:255',
            'display_name_en' => 'nullable|max:255',

        ];
    }
}
