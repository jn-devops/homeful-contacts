<?php

use Homeful\Contacts\Classes\ContactMetaData;
use App\Models\Contact;

return [
    'expiry' => env('REFERENCE_EXPIRY_INTERVAL', 'P30D'),//https://en.wikipedia.org/wiki/ISO_8601#Durations
    'models' => [
        'input' => \Homeful\References\Models\Input::class,
        'lead' => \Homeful\KwYCCheck\Models\Lead::class,
        'contract' => \Homeful\Contracts\Models\Contract::class,
        'contact' => Contact::class,
    ],
    'data' => [
        'lead' => \Homeful\KwYCCheck\Data\LeadData::class,
        'contract' => \Homeful\Contacts\Data\ContactData::class,
        'contact' => ContactMetaData::class,
    ],
];
