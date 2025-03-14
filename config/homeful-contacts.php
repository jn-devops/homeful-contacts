<?php

use Illuminate\Support\Str;

use App\Notifications\SendContactReferenceCodeNotification;
use App\Notifications\SendLoginMagicLinkNotification;
use App\Notifications\UnqualifiedUserNotification;

return [
    'minimum_income' => env('MINIMUM_INCOME', 12500),
    'callback' => env('CALLBACK'),
    'show_gmi' => (bool) env('SHOW_GMI', FALSE),
    'hide_password' => (bool) env('HIDE_PASSWORD', FALSE),
    'default_password' => env('DEFAULT_PASSWORD', Str::password()),
    'channels' => [
        'default' => array_filter(explode(',', env('DEFAULT_CHANNELS', 'database'))),
        'allowed' => array_filter(explode(',', env('ALLOWED_CHANNELS', 'database,slack'))),
        SendContactReferenceCodeNotification::class => array_filter(explode(',', env('SEND_CONTACT_REFERENCE_CODE', 'mail,engage_spark'))),
        SendLoginMagicLinkNotification::class => array_filter(explode(',', env('SEND_LOGIN_MAGIC_LINK', 'mail,engage_spark'))),
        UnqualifiedUserNotification::class => array_filter(explode(',', env('SEND_UNQUALIFIED_USER', 'mail'))),
    ],
    'support' => [
        'email' => env('SUPPORT_EMAIL', 'devops@joy-nostalg.com')
    ],
    'lazarus_api_token' => env('LAZARUS_API_TOKEN'),
    'lazarus_url' => env('LAZARUS_URL', 'http://homeful-lazarus.test'),
];
