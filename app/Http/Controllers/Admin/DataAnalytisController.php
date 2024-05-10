<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Admin\DataAnalytis\RevenueStatisticsRequest;
use App\Http\Requests\Admin\DataAnalytis\YearlyRevenueStatisticsRequest;
use App\Http\Requests\Admin\DataAnalytis\MonthlyRevenueStatisticsRequest;
use App\Http\Requests\Admin\DataAnalytis\StatusMotoStatisticsRequest;
use App\Http\Requests\MotoRevenueRequest;
use App\Http\Requests\MotoTypeRevenueRequest;
use App\Http\Resources\MotoResource;
use App\Http\Resources\MotoTypeResource;
use App\Services\Admin\DataAnalytisService;
use Illuminate\Support\Facades\Log;
use Throwable;

class DataAnalytisController extends BaseApiController
{
    public function __construct(protected DataAnalytisService $dataAnalytisSer)
    {
        //
    }

    public function getRevenue(RevenueStatisticsRequest $rq)
    {
        try {
            $revenue = $this->dataAnalytisSer->setRequest($rq)->getRevenue();
            return $this->sendResponse([
                "revenue" => $revenue
            ]);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage());
        }
    }

    public function getYearlyRevenue(YearlyRevenueStatisticsRequest $rq)
    {
        try {
            $revenueYearly = $this->dataAnalytisSer->setRequest($rq)->getYearlyRevenue();
            return $this->sendResponse($revenueYearly);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage());
        }
    }

    public function getMonthlyRevenue(MonthlyRevenueStatisticsRequest $rq)
    {
        try {
            $revenueMonthly = $this->dataAnalytisSer->setRequest($rq)->getMonthlyRevenue();
            return $this->sendResponse($revenueMonthly);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage());
        }
    }

    public function getMotoStatus(StatusMotoStatisticsRequest $rq)
    {
        try {
            return $this->sendResponse(
                $this->dataAnalytisSer->setRequest($rq)->getMotoStatus()
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage());
        }
    }

    public function getMotoType()
    {
        try {
            return $this->sendResponse(
                $this->dataAnalytisSer->getMotoType()
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage());
        }
    }

    public function getTopMotosByRevenue(MotoRevenueRequest $rq)
    {
        try {
            $motos = $this->dataAnalytisSer->getTopMotosByRevenue($rq->all());
            return $this->sendResponse(
                MotoResource::collection($motos)
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage());
        }
    }

    public function getTopMotoTypesByRevenue(MotoTypeRevenueRequest $rq)
    {
        try {
            $motoTypes = $this->dataAnalytisSer->getTopMotoTypesByRevenue($rq->all());
            return $this->sendResponse(
                MotoTypeResource::collection($motoTypes)
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage());
        }
    }
}
