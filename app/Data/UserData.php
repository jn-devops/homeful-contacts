<?php

namespace App\Data;

use Homeful\Contacts\Classes\ContactMetaData;
use Spatie\LaravelData\Data;

class UserData extends Data
{
    public function __construct(
        public string $name,
        public string $email,
        public string $mobile,
        public string $first_name,
        public string $last_name,
        public ?string $name_suffix,
        public ContactMetaData|null $contact
    ) {}
}
