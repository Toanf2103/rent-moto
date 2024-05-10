<?php

namespace App\Repositories\Holiday;

use App\Models\Holiday;
use App\Models\Order;
use App\Repositories\BaseRepository;
use Carbon\Carbon;

class HolidayRepository extends BaseRepository implements HolidayRepositoryInterface
{
    public function getModel()
    {
        return Holiday::class;
    }

    public function paginate($filters)
    {
        return $this->model
            ->when(isset($filters['date']), function ($query) use ($filters) {
                return $query->where('date', $filters['date']);
            })
            ->when(isset($filters['start_date']) && isset($filters['end_date']), function ($query) use ($filters) {
                $query->whereRaw(
                    "CONCAT(YEAR(NOW()), '-', date) 
                    BETWEEN 
                    CONCAT(YEAR(NOW()), '-', ?) AND CONCAT(YEAR(NOW()), '-', ?)",
                    [$filters['start_date'], $filters['end_date']]
                );
            })
            ->paginate($filters['per_page'] ?? 15);
    }

    public function getHolidaysOfOrder(Order $order)
    {
        $startYear = Carbon::parse($order->start_date)->year;
        $endYear = Carbon::parse($order->end_date)->year;
        return $this->model
            ->whereRaw(
                "CONCAT({$startYear}, '-', date) 
                BETWEEN 
                ? AND ?",
                [$order->start_date, $order->end_date]
            )
            ->when($startYear !== $endYear, function ($query) use ($order, $endYear) {
                return $query->orWhereRaw(
                    "CONCAT({$endYear}, '-', date) 
                    BETWEEN 
                    ? AND ?",
                    [$order->start_date, $order->end_date]
                );
            })
            ->get();
    }
}
