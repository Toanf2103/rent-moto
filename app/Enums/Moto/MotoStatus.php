<?php

namespace App\Enums\Moto;

use App\Traits\EnumToArray;

enum MotoStatus: string
{
    use EnumToArray;

    case ACTIVE = 'active';
    case LOST = 'lost';
    case REPAIR = 'repair';
    case RENT = 'rent';
    case BLOCK = 'block';

    static function readyRent(): array
    {
        return [self::ACTIVE, self::RENT];
    }

    static function readyRentValues(): array
    {
        return array_column(self::readyRent(), 'value');
    }
}
