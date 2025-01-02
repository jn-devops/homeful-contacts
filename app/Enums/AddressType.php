<?php

namespace App\Enums;

use App\Traits\EnumUtils;

/**
 * @deprecated
 */
enum AddressType: string
{
    use EnumUtils;

    case PRIMARY = 'Primary';
    case SECONDARY = 'Secondary';
    case WORK = 'Work';//Employer Address is probably better

    static function default(): self {
        return self::PRIMARY;
    }
}
