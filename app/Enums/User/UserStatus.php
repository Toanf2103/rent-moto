<?php

namespace App\Enums\User;

use App\Traits\EnumToArray;

enum UserStatus: string
{
    use EnumToArray;

    case REGISTER = 'register';
    case ACTIVE = 'active';
    case BLOCK = 'block';
}
