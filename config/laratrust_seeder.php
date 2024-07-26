<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => false,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    'roles_structure' => [
        'super-admin' => [
            'admins' => 'c,r,u,d',
            'teachers' => 'c,r,u,d',
            'students' => 'c,r,u,d',
            'customs' => 'c,r,u,d',
            'fields' => 'c,r,u,d',
            'categories' => 'c,r,u,d',
            'courses' => 'c,r,u,d',
            'standards' => 'c,r,u,d',
            'lectures' => 'c,r,u,d,l',
            'books' => 'c,r,u,d',
            'attachments' => 'c,r,u,d',
            'exams' => 'c,r,u,d',
            'coupons' => 'c,r,u,d',
            'carts' => 'r',
            'payments' => 'r,u',
            'printRequests' => 'r,u',
            'structure' => 'r,u',
            'solutions' => 'c,r,u,d',
            'inquiries' => 'r,u,d',
            'contacts' => 'r,d',
            'notifications' => 'r',

        ],
        'admin' => [
            'admins' => 'c,r,u,d',
            'teachers' => 'c,r,u,d',
            'students' => 'c,r,u,d',
            'customs' => 'c,r,u,d',
            'fields' => 'c,r,u,d',
            'categories' => 'c,r,u,d',
            'courses' => 'c,r,u,d',
            'standards' => 'c,r,u,d',
            'lectures' => 'c,r,u,d',
            'books' => 'c,r,u,d',
            'attachments' => 'c,r,u,d',
            'exams' => 'c,r,u,d',
            'coupons' => 'c,r,u,d',
            'carts' => 'r',
            'payments' => 'r,u',
            'printRequests' => 'r,u',
            'solutions' => 'c,r,u,d',
            'inquiries' => 'r,u,d',
            'contacts' => 'r,d',

        ],
        'teacher' => [
            'courses' => 'c,r,u,d',
            'standards' => 'c,r,u,d',
            'lectures' => 'c,r,u,d',
            'books' => 'c,r,u,d',
            'attachments' => 'c,r,u,d',
            'exams' => 'c,r,u,d',
            'solutions' => 'c,r,u,d',
            'inquiries' => 'r,u,d',
        ],
    ],

    'roles_translations' => [
        'super-admin' => [
            'en' => 'Super Admin',
            'ar' => 'مشرف عام'
        ],
        'admin' => [
            'en' => 'Admin',
            'ar' => 'مشرف'
        ],
        'teacher' => [
            'en' => 'Teacher',
            'ar' => 'معلم'
        ],
    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete',
        'l' => 'links'
    ]
];
