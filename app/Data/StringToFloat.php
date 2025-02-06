<?php

namespace App\Data;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Casts\Castable;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

class StringToFloat implements Castable, Cast
{
    public function __construct(public ?float $number) {}

    public static function dataCastUsing(...$arguments): Cast
    {
        return new self();
    }

    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        // Convert the value to a float if it's numeric, otherwise return null
        if (is_numeric($value)) {
            return (float) $value;
        }

        // Return null for non-numeric values or empty strings
        return null;
    }
}
