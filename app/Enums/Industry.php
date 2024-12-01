<?php

namespace App\Enums;

use App\Traits\EnumUtils;

enum Industry: string
{
    use EnumUtils;

    case BPO = 'BPO';
    case MEDICAL = 'Medical';
    case MARITIME = 'Maritime';

    static function default(): self {
        return self::BPO;
    }
}
