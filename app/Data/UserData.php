<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class UserData extends Data
{
    public function __construct(
        public string $name,
        public string $email,
        public string $mobile,
        public string $first_name,
        public string $last_name
    ) {}
}
