<?php

namespace App\Enums\MotoType;

use App\Traits\EnumToArray;

enum MotoTypeStatus: string
{
    use EnumToArray;

    case ACTIVE = 'active';
    case BLOCK = 'block';
}
