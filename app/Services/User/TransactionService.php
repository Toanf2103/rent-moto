<?php

namespace App\Services\User;

use App\Repositories\Transaction\TransactionRepositoryInterface;
use App\Services\BaseService;

class TransactionService extends BaseService
{
    public function __construct(protected TransactionRepositoryInterface $transacRep)
    {
        //
    }
}
