<?php

namespace App\Enums;

use App\Traits\EnumUtils;

enum Sex: string
{
    use EnumUtils;

    case MALE = 'Male';
    case FEMALE = 'Female';
}
