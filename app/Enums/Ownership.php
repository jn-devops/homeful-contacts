<?php

namespace App\Enums;

use App\Traits\EnumUtils;

enum Ownership: string
{
    use EnumUtils;

    case OWNED = 'Owned';
    case RENTED = 'Rented';

    static function default(): self {
        return self::RENTED;
    }
}
