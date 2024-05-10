<?php

namespace App\Services\User;

use App\Models\Order;
use App\Repositories\Holiday\HolidayRepositoryInterface;
use App\Repositories\OrderHoliday\OrderHolidayRepositoryInterface;
use App\Services\BaseService;

class OrderHolidayService extends BaseService
{
    public function __construct(
        protected HolidayRepositoryInterface $holidayRepo,
        protected OrderHolidayRepositoryInterface $orderHolidayRepo,
    ) {
        //
    }

    public function handleCreate(Order $order)
    {
        $holidays = $this->holidayRepo->getHolidaysOfOrder($order);
        if ($holidays->count() == 0) {
            return;
        }
        $data = $holidays->map(function ($holiday) use ($order) {
            return [
                'order_id' => $order->id,
                'holiday_id' => $holiday->id,
                'precent' => $holiday->precent,
            ];
        })->toArray();
        return $this->orderHolidayRepo->insert($data);
    }
}
