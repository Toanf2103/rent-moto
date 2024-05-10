<?php

namespace App\Repositories\OrderHoliday;

use App\Models\OrderHoliday;
use App\Repositories\BaseRepository;

class OrderHolidayRepository extends BaseRepository implements OrderHolidayRepositoryInterface
{
    public function getModel()
    {
        return OrderHoliday::class;
    }
}
