<?php

use App\Notifications\SendContactReferenceCodeNotification;
use App\Notifications\SendLoginMagicLinkNotification;

return [
    'channels' => [
        'default' => array_filter(explode(',', env('DEFAULT_CHANNELS', 'database'))),
        'allowed' => array_filter(explode(',', env('ALLOWED_CHANNELS', 'database,slack'))),
        SendContactReferenceCodeNotification::class => array_filter(explode(',', env('SEND_CONTACT_REFERENCE_CODE', 'mail,engage_spark'))),
        SendLoginMagicLinkNotification::class => array_filter(explode(',', env('SEND_LOGIN_MAGIC_LINK', 'mail,engage_spark'))),
    ],
];
