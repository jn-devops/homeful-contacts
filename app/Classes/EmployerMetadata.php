<?php

namespace App\Classes;

use App\Enums\Industry;
use Spatie\LaravelData\Data;
use App\Enums\Nationality;

class EmployerMetadata extends Data
{
    public function __construct(
        public string $name,
        public ?string $email,
        public ?string $contact_no,
        public ?Nationality $nationality,
        public ?Industry $industry,
        public ?AddressMetadata $address
    ) {}
}
