<?php

use Illuminate\Support\Str;

use App\Notifications\SendContactReferenceCodeNotification;
use App\Notifications\SendLoginMagicLinkNotification;
use App\Notifications\UnqualifiedUserNotification;

return [
    'contract_url' => env('CONTRACT_URL', 'http://homeful-contracts.test'),
];
