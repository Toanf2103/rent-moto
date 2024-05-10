<?php

namespace App\Enums\Transaction;

use App\Traits\EnumToArray;

enum TransactionType: int
{
    use EnumToArray;

    case DEPOSIT = 1;
    case PAYMENT = 2;
    case INCURRED = 3;
    case DISCOUNT = 4;
}
