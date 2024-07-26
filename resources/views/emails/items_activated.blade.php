<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <style>
        * {
            direction: rtl !important;
        }
        body {
            font-family: Tahoma, sans-serif;
            margin: 0;
            padding: 0;
            direction: rtl;
        }

        .wrapper {
            max-width: 600px;
            margin: 0 auto;
        }

        .container {
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        h1 {
            text-align: center;
            color: #333333;
        }

        p {
            margin-bottom: 20px;
            color: #555555;
        }

        .button {
            display: inline-block;
            background: linear-gradient(
                90deg,
                #f4bf49 18.74%,
                #fad94a 93.41%
            );
            color: black;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }

        .button:hover {
            background-color: #ff8000;
        }

        img.logo {
            margin-top: 20px;
            display: block;
            width: 150px;
        }

        ul {
            list-style: none;
            text-align: center;
        }

        ul li {
            display: inline;
            margin: 0 10px;
        }

        li a {
            color: #2a7de5 !important;
        }

        li a:hover {
            color: #b98b00 !important;
        }

        .download {
            color: #2a7de5 !important;
            text-decoration: underline !important;
        }

        .download:hover {
            color: #b98b00 !important;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <img
        class="logo"
        src="https://meem-sa.com/_nuxt/img/meem.f868324.png"
        alt="logo"
    />
    <div class="container">
        <h1> تم تفعيل {{ $notifiable_items->count() == 1 ? 'كتاب' : $notifiable_items->count() . 'كتب' }} في حسابك!</h1>
        <h2>مرحباً {{ $user->name }},</h2>
        <h2>
            تم تفعيل في حسابك عدد: {{$notifiable_items->count()}} من الكتب المتوفرة لدينا بصيغة PDF.
        </h2>
        <h2 style="font-weight: bold;">يمكنك الوصول إليها في حسابك على منصة ميم التعليمية أو الوصول إليها من الروابط الآتية:</h2>
        <ul>
            @foreach($notifiable_items as $item)
                <li>
                    <p style="font-size: 20px;">- {{ $item->cartable->t('name') }}. <a class="download" href="https://{{ env('PRODUCTION_DL_SUBDOMAIN') . '/' . $item->cartable->getRawOriginal('book_pdf') }}">تحميل PDF</a></p>
                </li>
                <br>
            @endforeach

        </ul>
    </div>
    <ul>
        <li><a href="https://meem-sa.com/faqs/">الأسئلة الشائعة</a></li>
        <li><a href="https://meem-sa.com/about/">عن المنصة</a></li>
        <li><a href="https://meem-sa.com/privacy-policy/">سياسة الخصوصية</a></li>
        <li><a href="https://meem-sa.com/terms-conditions/">الشروط والأحكام</a></li>
    </ul>
</div>
</body>
</html>
