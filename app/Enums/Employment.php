<?php

namespace App\Enums;

use App\Traits\EnumUtils;

/**
 * @deprecated
 */
enum Employment: string
{
    use EnumUtils;

    case PRIMARY = 'Primary';
    case SIDELINE = 'Sideline';

    static function default(): self {
        return self::PRIMARY;
    }
}
