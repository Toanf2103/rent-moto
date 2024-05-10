<?php

namespace App\Enums\Order;

use App\Traits\EnumToArray;

enum OrderStatus: string
{
    use EnumToArray;

    case WAIT = 'wait';
    case APPROVE = 'approve';
    case CANCEL = 'cancel';
    case RENT = 'rent';
    case DENY = 'deny';
    case COMPLETE = 'complete';

    static function canUpdate(): array
    {
        return [self::WAIT, self::APPROVE];
    }
}
