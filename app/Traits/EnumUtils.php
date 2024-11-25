<?php

namespace App\Traits;

use Illuminate\Validation\Rules\In;
use Illuminate\Validation\Rule;

trait EnumUtils
{
    static function random(): self {
        $enums = self::cases(); return $enums[array_rand($enums)];
    }

    static function toArray(): array {
       return array_column(self::cases(), 'name');
    }

    static function rule(): In
    {
        return Rule::in(self::toArray());
    }
}
