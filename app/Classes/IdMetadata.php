<?php

namespace App\Classes;

use Spatie\LaravelData\Data;

class IdMetadata extends Data
{
    public function __construct(
        public string $tin, //better if BIR?
        public ?string $pagibig,
        public ?string $sss,
        public ?string $gsis,
    ) {}
}
