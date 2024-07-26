<?php

namespace App\Http\Requests\Dashboard\Courses;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookRequest extends FormRequest
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
            'books.0.name_ar' => 'required|max:255',
            'books.0.name_en' => 'nullable|max:255',
            'books.0.book_pdf' => 'mimes:pdf|' . ($this->isMethod('POST') ? 'required' : ''),
//            'books.0.course_id' => ['nullable'],
            'books.0.price' => 'required',
            'books.0.description_ar' => 'nullable',
            'books.0.description_en' => 'nullable',
            'books.0.image' => 'nullable',
            'books.0.show_in_store' => 'nullable'
//            'books.0.is_bw' => 'nullable',
//            'books.0.bw_price' => 'sometimes|required_with:books.0.is_bw',
//            'books.0.is_coloured' => 'nullable',
//            'books.0.coloured_price' => 'sometimes|required_with:books.0.is_coloured',
        ];
    }
}
