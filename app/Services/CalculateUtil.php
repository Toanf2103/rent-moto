<?php

namespace App\Services;

use App\Models\Order;

class CalculateUtil
{
    public function __construct()
    {
        //
    }

    public function calculatePriceTotalPay($priceMoto, Order $order)
    {
        $dateRentNumber = diffDate($order->start_date, $order->end_date) + 1;
        $totalPriceHoliday = $order->holidays->reduce(function ($carry, $holiday) use ($priceMoto) {
            return $carry + $priceMoto * $holiday->precent / 100;
        }, 0);
        //total = amount of holidays + remaining days
        $total = $totalPriceHoliday + $priceMoto * ($dateRentNumber - $order->holidays->count());
        return $total * $order->rent_package_percent / 100;
    }
}
