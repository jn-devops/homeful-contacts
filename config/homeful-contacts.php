<?php

use Illuminate\Support\Str;

use App\Notifications\SendContactReferenceCodeNotification;
use App\Notifications\SendLoginMagicLinkNotification;

return [
    'minimum_income' => env('MINIMUM_INCOME', 12500),
    'callback' => env('CALLBACK'),
    'show_gmi' => (bool) env('SHOW_GMI', FALSE),
    'hide_password' => (bool) env('HIDE_PASSWORD', FALSE),
    'default_password' => env('DEFAULT_PASSWORD', Str::password()),
    'channels' => [
        'default' => array_filter(explode(',', env('DEFAULT_CHANNELS', 'database'))),
        'allowed' => array_filter(explode(',', env('ALLOWED_CHANNELS', 'database,slack'))),
        SendContactReferenceCodeNotification::class => array_filter(explode(',', env('SEND_CONTACT_REFERENCE_CODE', 'mail,sms'))),
        SendLoginMagicLinkNotification::class => array_filter(explode(',', env('SEND_LOGIN_MAGIC_LINK', 'mail,sms'))),
    ],
];
