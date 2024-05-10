<?php

namespace App\Enums\User;

use App\Traits\EnumToArray;

enum UserRole: string
{
    use EnumToArray;

    case ADMIN = 'admin';
    case EMPLOYEE = 'employee';
    case USER = 'user';
}
