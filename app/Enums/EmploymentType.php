<?php

namespace App\Enums;

use App\Traits\EnumUtils;

enum EmploymentType: string
{
    use EnumUtils;

    case LOCALLY_EMPLOYED = 'Locally Employed';
    case SELF_EMPLOYED = 'Self-Employed';
    case OFW = 'OFW';

    static function default(): self {
        return self::LOCALLY_EMPLOYED;
    }
}
