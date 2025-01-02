<?php

namespace App\Classes;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

/**
 * @deprecated
 */
class ReferenceMetadata extends Data
{
    public function __construct(
        public string $code,
        public ?array $metadata,
        public ?Carbon $starts_at,
        public ?Carbon $expires_at,
        public ?Carbon $redeemed_at,
        public ContactMetaData $contact
    ) {}
}
