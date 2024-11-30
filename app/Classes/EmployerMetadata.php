<?php

namespace App\Classes;

use Spatie\LaravelData\Data;

class EmployerMetadata extends Data
{
    public function __construct(
        public string $name,
    ) {}
}
