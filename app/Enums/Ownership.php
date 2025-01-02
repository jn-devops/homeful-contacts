<?php

namespace App\Enums;

use App\Traits\EnumUtils;

/**
 * @deprecated
 */
enum Ownership: string
{
    use EnumUtils;

    case OWNED = 'Owned';
    case RENTED = 'Rented';
    case UNKNOWN = 'Unknown';

    static function default(): self {
        return self::UNKNOWN;
    }
}
