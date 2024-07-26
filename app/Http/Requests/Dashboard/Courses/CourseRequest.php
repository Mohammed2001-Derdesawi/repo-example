<?php

namespace App\Http\Requests\Dashboard\Courses;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseRequest extends FormRequest
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
            'description_ar' => 'required',
            'description_en' => 'nullable',
            'price' => 'required|numeric',
            'app_price' => 'nullable|numeric',
            'category_id' => ['required' , Rule::exists('categories' , 'id')],
            'start_date' => 'required|' . ($this->isMethod('POST') ? 'after:yesterday' : ''),
            'end_date' => 'nullable|after:start_date' ,
            'duration' => 'nullable|gt:0' ,
            'whatsapp_link' => 'nullable|url' ,
            'telegram_link' => 'nullable|url' ,
            'telegram_channel_link' => 'nullable|url' ,
            'show_teacher_names' => 'required|boolean',
            'request_certificate_available' => 'nullable',
            'certificate_price' => 'nullable',
            'is_active' => 'nullable',
            'registration_status' => 'nullable',
            'important_flag' => 'nullable',
            'sort' => 'required|integer|gte:1',
            'is_ratable' => 'nullable',
            'notcontinue' => 'nullable',
            'image' => 'nullable|exclude|image|mimes:jpg,jpeg,png|max:2048',
            'explanation_video' => 'nullable',
            'explanation_video_platform' => 'nullable',
            'profile_file' => 'nullable|exclude|max:2048',
            'books.*.book_pdf' => 'nullable|mimes:pdf',
            'books.*.name_ar' => 'required',
            'books.*.name_en' => 'nullable',
//            'books.*.is_bw' => 'nullable',
//            'books.*.bw_price' => 'sometimes|required_with:books.*.is_bw',
//            'books.*.is_coloured' => 'nullable',
//            'books.*.coloured_price' => 'sometimes|required_with:books.*.is_coloured',
            'books.*.price' => 'required',
            'books.*.image' => 'nullable',
            'attachments.*.file' => 'sometimes|required|mimes:pdf',
            'attachments.*.name_ar' => 'sometimes|max:255|required_with:attachments.*.file',
            'attachments.*.name_en' => 'nullable|max:255',
            'attachments.*.is_active' => 'nullable',
            'teachers' => 'required|array|min:1',
            'send_auto_whatsapp_message'=>'nullable',
            'whatsapp_image_url'=>'nullable',
            'whatsapp_message'=>'nullable',
        ];
    }
}
