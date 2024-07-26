<?php

namespace Database\Seeders;

use App\Models\Structure;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiteContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Structure::query()->updateOrCreate([
            'key' => 'Home',
            'content' => json_encode([
                'en' => [
                    "section1" => [
                        'title' => "Section 1 Title",
                        'description' => "Description",
                        'button' => 'Button Text',
                        'image' => 'www.image.com'
                    ],
                    'section2' => [
                        'title' => 'Section 2 Title',
                        'more' => 'More'
                    ],
                    'section3' => [
                        'title' => 'Section 3 Title',
                        'description' => 'Description',
                        'features' => [
                             [
                                'image' => 'www.icon.com',
                                'title' => 'Feature 1 Title ',
                                'description' => 'Description 1'
                            ],
                            [
                                'image' => 'www.icon.com',
                                'title' => 'Feature 2 Title',
                                'description' => 'Description 2'
                            ],
                             [
                                'image' => 'www.icon.com',
                                'title' => 'Feature 3 Title',
                                'description' => 'Description 3'
                            ],
                            [
                                'image' => 'www.icon.com',
                                'title' => 'Feature 4 Title',
                                'description' => 'Description 4'
                            ]

                        ]
                    ],
                    'section4' => [
                        'title' => "Section 4 Title"
                    ],
                    'section5' => [
                        'title' => 'Section 5 Title',
                        'images' => [
                            [
                                'id' => 1,
                                'image' => 'image1.com'
                            ],
                            [
                                'id' => 2,
                                'image' => 'image1.com'
                            ],
                            [
                                'id' => 3,
                                'image' => 'image1.com'
                            ]
                        ]
                    ],
                    'section6' => [
                        'title' => 'Section 6 Title',
                        'accounts' => [
                            [
                                'platform' => "SNAPCHAT",
                                'account' => 'www.snapchat.com'
                            ],
                            [
                                'platform' => "TWITTER",
                                'account' => 'www.twitter.com'
                            ],
                            [
                                'platform' => "TIKTOK",
                                'account' => 'www.tiktok.com'
                             ],
                            [
                                'platform' => "FACEBOOK",
                                'account' => 'www.facebook.com'
                            ]

                        ]
                    ]
                ] ,
                'ar' => [
                    "section1" => [
                        'title' => "عنوان قسم 1",
                        'description' => "وصف قسم 1",
                        'button' => 'زر 1',
                        'image' => 'www.image.com'
                    ],
                    'section2' => [
                        'title' => 'عنوان قسم 2',
                        'more' => 'المزيد'
                    ],
                    'section3' => [
                        'title' => 'عنوان قسم 3',
                        'description' => 'وصف قسم 3',
                        'features' => [
                            [
                                'image' => 'www.icon.com',
                                'title' => 'عنوان ميزة 1 ',
                                'description' => 'وصف ميزة 1'
                            ],
                            [
                                'image' => 'www.icon.com',
                                'title' => 'عنوان ميزة 2',
                                'description' => 'وصف ميزة 2'
                            ],
                            [
                                'image' => 'www.icon.com',
                                'title' => 'عنوان ميزة 3',
                                'description' => 'وصف ميزة 3'
                            ],
                            [
                                'image' => 'www.icon.com',
                                'title' => 'عنوان ميزة 4',
                                'description' => 'وصف ميزة 4'
                            ]

                        ]
                    ],
                    'section4' => [
                        'title' => "عنوان قسم 4"
                    ],
                    'section5' => [
                        'title' => 'عنوان قسم 5',
                        'images' => [
                            'image1.com',
                            'image2.com',
                            'image3.com'
                        ]
                    ],
                    'section6' => [
                        'title' => 'عنوان القسم السادس',
                        'accounts' => [
                            [
                                'platform' => "SNAPCHAT",
                                'account' => 'www.snapchat.com'
                            ],
                            [
                                'platform' => "TWITTER",
                                'account' => 'www.twitter.com'
                            ],
                            [
                                'platform' => "TIKTOK",
                                'account' => 'www.tiktok.com'
                            ],
                            [
                                'platform' => "FACEBOOK",
                                'account' => 'www.Instagram.com'
                            ]

                        ]
                    ]
                ] ,

            ])
        ]);

        Structure::query()->updateOrCreate([
            'key' => "privacy_and_policy",
            'content' => json_encode([
                'en' => [
                    'title' => 'Privacy & Policy',
                    'section1' => [
                        'title' => 'Terms Of Use',
                        'description' => 'Section 1 Description',
                    ],
                    'section2' => [
                        'title' => 'Fees and Payments',
                        'description' => 'Section 2 Description'
                    ]
                ],
                'ar' => [
                    'title' => 'سياسة الخصوصية',
                    'section1' => [
                        'title' => 'شروط الاستخدام',
                        'description' => 'وصف القسم الاول',
                    ],
                    'section2' => [
                        'title' => 'الرسوم والمدفوعات',
                        'description' => 'وصف القسم الثاني'
                    ]
                ]
            ])
        ]);

        Structure::query()->updateOrCreate(
            ['key' => 'about-us'],
            ['content' => json_encode([
                'ar' => [
                    'section1' => [
                        'title' => 'عن المنصة',
                        'description' => 'هنالك العديد من الأنواع المتوفرة لنصوص لوريم إيبسوم، ولكن الغالبية تم تعديلها بشكل ما عبر إدخال بعض النوادر أو الكلمات العشوائية إلى النص. إن كنت تريد أن تستخدم نص لوريم إيبسوم ما، عليك أن تتحقق أولاً أن ليس هناك أي كلمات أو عبارات محرجة أو غير لائقة مخبأة في هذا النص. بينما تعمل جميع مولّدات نصوص لوريم إيبسوم على الإنترنت على إعادة تكرار مقاطع من نص لوريم إيبسوم نفسه عدة مرات بما تتطلبه الحاجة، يقوم مولّدنا هذا باستخدام كلمات من قاموس يحوي على أكثر من 200 كلمة لا تينية، مضاف إليها مجموعة من الجمل النموذجية، لتكوين نص لوريم إيبسوم ذو شكل منطقي قريب إلى النص الحقيقي. وبالتالي يكون النص الناتح خالي من التكرار، أو أي كلمات أو عبارات غير لائقة أو ما شابه. وهذا ما يجعله أول مولّد نص لوريم إيبسوم حقيقي على الإنترنت.',
                        'image' => 'https://www.image.com'
                    ],
                    'section2' => [
                        'title' => 'شركاء النجاح',
                        'partners' => [
                            [
                                'image' => 'https://www.image.com',
                                'url' => 'https://www.partner.com'
                            ],
                            [
                                'image' => 'https://www.image.com',
                                'url' => 'https://www.partner.com'
                            ],
                            [
                                'image' => 'https://www.image.com',
                                'url' => 'https://www.partner.com'
                            ],
                            [
                                'image' => 'https://www.image.com',
                                'url' => 'https://www.partner.com'
                            ],
                        ],
                    ],
                ],
                'en' => [
                    'section1' => [
                        'title' => 'About Us',
                        'description' => 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).',
                        'image' => 'https://www.image.com'
                    ],
                    'section2' => [
                        'title' => 'Success Partners',
                        'partners' => [
                            [
                                'image' => 'https://www.image.com',
                                'url' => 'https://www.partner.com'
                            ],
                            [
                                'image' => 'https://www.image.com',
                                'url' => 'https://www.partner.com'
                            ],
                            [
                                'image' => 'https://www.image.com',
                                'url' => 'https://www.partner.com'
                            ],
                            [
                                'image' => 'https://www.image.com',
                                'url' => 'https://www.partner.com'
                            ],
                        ],
                    ],
                ],
            ])]
        );

         Structure::query()->updateOrCreate([
             "key" => 'common_questions',
             'content' => json_encode([
               'en' => [
                   'title' => "Common Questions",
                   'questions' => [
                       [
                           'question' => "Test",
                           'answer' => 'Test Answer'
                       ],
                       [
                           'question' => "Test",
                           'answer' => 'Test Answer'
                       ],
                       [
                           'question' => "Test",
                           'answer' => 'Test Answer'
                       ],
                       [
                           'question' => "Test",
                           'answer' => 'Test Answer'
                       ],
                       [
                           'question' => "Test",
                           'answer' => 'Test Answer'
                       ],
                       [
                           'question' => "Test",
                           'answer' => 'Test Answer'
                       ],
                       [
                           'question' => "Test",
                           'answer' => 'Test Answer'
                       ]

                   ]
               ],
               'ar' => [
                     'title' => "الاسئلة الشائعة",
                     'questions' => [
                         [
                             'question' => "سؤال",
                             'answer' => 'جواب'
                         ],
                         [
                             'question' => "سؤال",
                             'answer' => 'جواب'
                         ],
                         [
                             'question' => "سؤال",
                             'answer' => 'جواب'
                         ],
                         [
                             'question' => "سؤال",
                             'answer' => 'جواب'
                         ],
                         [
                             'question' => "سؤال",
                             'answer' => 'جواب'
                         ],
                         [
                             'question' => "سؤال",
                             'answer' => 'جواب'
                         ],
                         [
                             'question' => "سؤال",
                             'answer' => 'جواب'
                         ],

                     ]
                 ]
             ])
         ]);

        Structure::query()->updateOrCreate(
            ['key' => 'contact-us'],
            ['content' => json_encode([
                'ar' => [
                    'title' => 'تواصل معنا',
                    'section1' => [
                        'title' => 'كن على تواصل معنا',
                        'description' => 'رضا عملاؤنا هو من أولوياتنا الرئيسية.',
                    ],
                    'section2' => [
                        [
                            'icon' => 'address',
                            'title' => 'العنوان',
                            'description' => 'شارع الدمام 12345, المملكة العربية السعودية',
                        ],
                        [
                            'icon' => 'email',
                            'title' => 'البريد الإلكتروني',
                            'description' => 'meem.com',
                        ],
                        [
                            'icon' => 'phone',
                            'title' => 'رقم الجوال',
                            'description' => '+966 12 345 6789',
                        ],
                    ],
                ],
                'en' => [
                    'title' => 'Contact Us',
                    'section1' => [
                        'title' => 'Get in touch with us',
                        'description' => 'Our customers\' satisfaction is our main priority.',
                    ],
                    'section2' => [
                        [
                            'icon' => 'address',
                            'title' => 'Address',
                            'description' => 'Dammam Street 12345, KSA',
                        ],
                        [
                            'icon' => 'email',
                            'title' => 'Email',
                            'description' => 'meem.com',
                        ],
                        [
                            'icon' => 'phone',
                            'title' => 'Phone',
                            'description' => '+966 12 345 6789',
                        ],
                    ],
                ]
            ])],
        );

        Structure::query()->updateOrCreate(
            ['key' => 'terms-and-conditions'],
            ['content' => json_encode([
                'ar' => [
                    'title' => 'الشروط والاحكام',
                    'text' => "fdsfjdsjhfjsdhfdsf"
                ],
                'en' => [
                    'title' => 'Terms And Conditions',
                    'text' => "fdsfjdsjhfjsdhfdsf"
                ],
            ])],
        );

    }
}
