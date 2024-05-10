<?php

namespace App\Enums\Holiday;

use App\Traits\EnumToArray;

enum HolidayStatus: int
{
    use EnumToArray;

    case ACTIVE = 1;
    case INACTIVE = 2;
}
