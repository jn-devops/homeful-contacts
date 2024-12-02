<?php

namespace App\Classes;

use App\Enums\{Industry, Nationality};
use Spatie\LaravelData\Data;
use App\Traits\WithAck;

class EmployerMetadata extends Data
{
    use WithAck;

    public function __construct(
        public string $name,
        public ?string $email,
        public ?string $contact_no,
        public ?Nationality $nationality,
        public ?Industry $industry,
        public ?AddressMetadata $address
    ) {}
}
