<?php

use App\Models\Course;
use App\Models\CourseBook;

return [
    'gender' => [
        'MALE' => 'ذكر',
        'FEMALE' => 'أنثى',
    ],
    'lecture_type' => [
        'RECORDED' => 'مُسجل',
        'LIVE' => 'بث مباشر',
    ],
    'exam_type' => [
        'LECTURE' => 'اختبار محاضرة',
        'STANDARD' => 'اختبار معيار',
        'COURSE' => 'اختبار دورة',
    ],
    'exam_type_single' => [
        'lecture' => 'المحاضرة',
        'standard' => 'المعيار',
        'course' => 'الدورة',
    ],
    'payment_type' => [
        'CASH' => 'تحويل بنكي',
        'EPAYMENT' => 'دفع إلكتروني',
        'TAMARA' => 'تمارا',
    ],
    'payment_buy_type' => [
        'CART' => 'عربة تسوق',
        'CERTIFICATE' => 'شهادة',
    ],
    'payment_confirmation' => [
        0 => 'لم يتم التأكيد',
        1 => 'تم التأكيد',
    ],
    'inquiries' => [
        'EDUCATIONAL' => 'سؤال تعليمي',
        'TECHNICAL' => 'سؤال تقني',
    ],
    'cart_item_type' => [
        Course::class => 'دورة',
        CourseBook::class => 'ملزمة',
    ]
];
