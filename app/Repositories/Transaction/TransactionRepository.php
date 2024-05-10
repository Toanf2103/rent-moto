<?php

namespace App\Repositories\Transaction;

use App\Enums\Transaction\TransactionStatus;
use App\Enums\Transaction\TransactionType;
use App\Models\Transaction;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TransactionRepository extends BaseRepository implements TransactionRepositoryInterface
{
    public function getModel()
    {
        return Transaction::class;
    }

    public function confirmPaymentDeposit($orderId)
    {
        return $this->model->where('order_id', $orderId)
            ->where('type', TransactionType::DEPOSIT)
            ->update(['status' => TransactionStatus::PAID, 'date_payment' => now()]);
    }

    public function complete($orderId)
    {
        return $this->model->where('order_id', $orderId)
            ->update(['status' => TransactionStatus::PAID, 'date_payment' => now()]);
    }

    public function getRevenue($filters)
    {
        $MIN_START_DATE_VALUE = "1990/01/01";
        $startDate = $filters['start_date'] ?? $MIN_START_DATE_VALUE;
        $endDate = $filters['end_date'] ?? now();
        return $this->model
            ->filtersRevenue($filters)
            ->whereBetween(DB::raw('DATE(date_payment)'), [$startDate, $endDate])
            ->sum('cost');
    }

    public function getYearlyRevenue($filters)
    {
        return $this->model
            ->selectRaw(DB::raw(
                'SUM(cost) as revenue, 
                YEAR(date_payment) as year, 
                count(transactions.order_id) as orderNumber'
            ))
            ->filtersRevenue($filters)
            ->when(isset($filters['years']), function ($query) use ($filters) {
                return $query->whereIn(DB::raw('YEAR(date_payment)'), $filters['years']);
            })
            ->groupBy('year')
            ->orderBy('year')
            ->get()
            ->toArray();
    }

    public function getMonthlyRevenue($filters)
    {
        $defaultData = [];
        for ($month = 1; $month <= 12; $month++) {
            $defaultData[Carbon::create($filters['year'], $month, 1)->format('Y-m')] = [
                "revenue" => 0,
                "month" => Carbon::create($filters['year'], $month, 1)->format('Y-m'),
                "orderNumber" => 0
            ];
        }
        $data = $this->model
            ->selectRaw(DB::raw(
                'SUM(cost) as revenue, 
                DATE_FORMAT(date_payment,"%Y-%m") AS month, 
                COUNT(transactions.order_id) as orderNumber'
            ))
            ->filtersRevenue($filters)
            ->whereYear('date_payment', $filters['year'])
            ->groupBy('month')
            ->get()
            ->toArray();
        foreach ($data as $item) {
            $defaultData[$item['month']] = [
                "revenue" => $item["revenue"],
                "month" => $item["month"],
                "orderNumber" => $item["orderNumber"]
            ];
        }
        return array_values($defaultData);
    }

    public function updateCostPayment($orderId, $paymentCost)
    {
        return $this->model
            ->where('order_id', $orderId)
            ->where('type', TransactionType::PAYMENT)
            ->update(['cost' => $paymentCost]);
    }

    public function refundMoney($orderId, $descriptions)
    {
        return $this->model->where('type', TransactionType::DEPOSIT)
            ->where('status', TransactionStatus::PAID)
            ->update(['status' => TransactionStatus::REFUND, 'descriptions' => $descriptions]);
    }

    public function updateTransaction(TransactionType $type, $orderId, $data)
    {
        return $this->model
            ->where('order_id', $orderId)
            ->where('type', $type)
            ->update($data);
    }
}
