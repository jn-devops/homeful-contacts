<?php

namespace App\Classes;

use App\Enums\{AddressType, Ownership};
use Spatie\LaravelData\Data;

class AddressMetadata extends Data
{
    public function __construct(
        public AddressType $type,
        public Ownership $ownership,
        public string $address1,
        public string $locality,
        public string $administrative_area,
        public string $postal_code,
        public string $region,
        public string $country
    ) {}
}
