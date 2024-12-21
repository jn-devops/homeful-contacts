<?php

namespace App\Data;

use App\Classes\ContactMetaData;
use Spatie\LaravelData\Data;

class UserData extends Data
{
    public function __construct(
        public string $name,
        public string $email,
        public string $mobile,
        public string $first_name,
        public string $last_name,
        public ContactMetaData|null $contact
    ) {}
}
