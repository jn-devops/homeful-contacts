<?php

namespace App\Classes;

use App\Enums\{AddressType, Ownership};
use Spatie\LaravelData\Data;
use App\Traits\WithAck;

/**
 * @deprecated
 */
class AddressMetadata extends Data
{
    use WithAck;

    public string $address;

    public function __construct(
        public AddressType $type,
        public Ownership $ownership,
        public string $address1,
        public string $locality,
        public string $administrative_area,
        public string $postal_code,
        public string $region,
        public string $country
    ) {
        $this->address = implode(', ', array_filter([$this->address1, $this->locality, $this->administrative_area, $this->postal_code]));
    }
}
