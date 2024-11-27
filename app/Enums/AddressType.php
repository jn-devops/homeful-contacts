<?php

namespace App\Enums;

use App\Traits\EnumUtils;

enum AddressType: string
{
    use EnumUtils;

    case PRIMARY = 'Primary';
    case SECONDARY = 'Secondary';

    static function default(): self {
        return self::PRIMARY;
    }
}
