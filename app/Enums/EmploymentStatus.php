<?php

namespace App\Enums;

use App\Traits\EnumUtils;

/**
 * @deprecated
 */
enum EmploymentStatus: string
{
    use EnumUtils;

    case REGULAR = 'Regular';
    case CONTRACTUAL = 'Contractual';

    static function default(): self {
        return self::REGULAR;
    }
}
