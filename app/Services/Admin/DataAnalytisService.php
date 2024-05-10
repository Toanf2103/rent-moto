<?php

namespace App\Services\Admin;

use App\Repositories\Moto\MotoRepositoryInterface;
use App\Repositories\MotoType\MotoTypeRepositoryInterface;
use App\Repositories\Transaction\TransactionRepositoryInterface;
use App\Services\BaseService;

class DataAnalytisService extends BaseService
{
    public function __construct(
        protected TransactionRepositoryInterface $transactionRepo,
        protected MotoRepositoryInterface $motoRepo,
        protected MotoTypeRepositoryInterface $motoTypeRepo
    ) {
        //
    }

    public function getRevenue()
    {
        return $this->transactionRepo->getRevenue($this->data);
    }

    public function getYearlyRevenue()
    {
        return $this->transactionRepo->getYearlyRevenue($this->data);
    }

    public function getMonthlyRevenue()
    {
        return $this->transactionRepo->getMonthlyRevenue($this->data);
    }

    public function getMotoStatus()
    {
        return $this->motoRepo->getStatusWithCount($this->data);
    }

    public function getMotoType()
    {
        return $this->motoTypeRepo->getMotoTypeStatistics($this->data);
    }

    public function getTopMotosByRevenue($filters)
    {
        return $this->motoRepo->getTopMotosByRevenue($filters);
    }

    public function getTopMotoTypesByRevenue($filters)
    {
        return $this->motoTypeRepo->getTopMotoTypesByRevenue($filters);
    }
}
