<?php

namespace App\Data;

use Spatie\LaravelData\Support\Transformation\TransformationContext;
use Spatie\LaravelData\Transformers\Transformer;
use Spatie\LaravelData\Support\DataProperty;

class StringToFloatTransformer implements Transformer
{

    public function transform(DataProperty $property, mixed $value, TransformationContext $context): mixed
    {
        if (is_numeric($value)) {
            return (float) $value;
        }

        // Return null for empty strings or non-numeric values
        return null;
    }
}
