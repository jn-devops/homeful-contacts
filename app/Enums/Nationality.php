<?php

namespace App\Enums;

use App\Traits\EnumUtils;

enum Nationality: string
{
    use EnumUtils;

    case FILIPINO = 'Filipino';
    case AMERICAN = 'American';
    case CHINESE = 'Chinese';
    case RUSSIAN = 'Russian';

    static function default(): self {
        return self::FILIPINO;
    }
}
