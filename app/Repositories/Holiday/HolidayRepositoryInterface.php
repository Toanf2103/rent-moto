<?php

namespace App\Repositories\Holiday;

use App\Models\Order;
use App\Repositories\RepositoryInterface;

interface HolidayRepositoryInterface extends RepositoryInterface
{
    public function paginate($filters);

    public function getHolidaysOfOrder(Order $order);
}
