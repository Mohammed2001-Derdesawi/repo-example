<?php
return [
    'mobile'=>[
        'current_version'=>env('CURRENT_VERSION_MOBILE','1.0.0.0'),
        'android_version'=>env('CURRENT_ANDROID_VERSION','1.0.0.0'),
        'apple_version'=>env('CURRENT_APPLE_VERSION','1.0.0.0'),
    ],
    'web'=>[
        'current_version'=>env('CURRENT_VERSION_WEB','v1'),
    ],
];
