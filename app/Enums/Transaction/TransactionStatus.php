<?php

namespace App\Enums\Transaction;

use App\Traits\EnumToArray;

enum TransactionStatus: int
{
    use EnumToArray;

    case UNPAID = 1;
    case PAID = 2;
    case REFUND = 3;
}
