<?php

namespace App\Classes;

use Spatie\LaravelData\Data;
use App\Traits\WithAck;

/**
 * @deprecated
 */
class IdMetadata extends Data
{
    use WithAck;

    public function __construct(
        public string $tin, //better if BIR?
        public ?string $pagibig,
        public ?string $sss,
        public ?string $gsis,
    ) {}
}
