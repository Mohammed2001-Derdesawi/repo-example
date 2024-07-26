<?php

use App\Models\Course;
use App\Models\CourseBook;

return [
    'gender' => [
        'MALE' => 'Male',
        'FEMALE' => 'Female',
    ],
    'lecture_type' => [
        'RECORDED' => 'Recorded',
        'LIVE' => 'Live',
    ],
    'exam_type' => [
        'LECTURE' => 'Lecture exam',
        'STANDARD' => 'Standard exam',
        'COURSE' => 'Course exam',
    ],
    'exam_type_single' => [
        'LECTURE' => 'Lecture',
        'STANDARD' => 'Standard',
        'COURSE' => 'Course',
    ],
    'payment_type' => [
        'CASH' => 'Cash',
        'EPAYMENT' => 'E-Payment',
        'TAMARA' => 'Tamara',
    ],
    'payment_buy_type' => [
        'CART' => 'Cart',
        'CERTIFICATE' => 'Certificate',
    ],
    'payment_confirmation' => [
        0 => 'Not Confirmed',
        1 => 'Confirmed',
    ],
    'inquiries' => [
        'EDUCATIONAL' => 'Educational Question',
        'TECHNICAL' => 'Technical Question',
    ],
    'cart_item_type' => [
        Course::class => 'Course',
        CourseBook::class => 'Book',
    ]
];
