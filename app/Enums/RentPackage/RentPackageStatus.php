<?php

namespace App\Enums\RentPackage;

use App\Traits\EnumToArray;

enum RentPackageStatus: string
{
    use EnumToArray;

    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
}
