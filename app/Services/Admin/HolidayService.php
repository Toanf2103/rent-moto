<?php

namespace App\Services\Admin;

use App\Models\Holiday;
use App\Repositories\Holiday\HolidayRepositoryInterface;
use App\Services\BaseService;

class HolidayService extends BaseService
{
    public function __construct(protected HolidayRepositoryInterface $holidayRepo)
    {
        //
    }

    public function create()
    {
        return $this->holidayRepo->create($this->data);
    }

    public function paginate($filters)
    {
        return $this->holidayRepo->paginate($filters);
    }

    public function update(Holiday $holiday)
    {
        return $this->holidayRepo->update($holiday->id, $this->data);
    }

    public function destroy(Holiday $holiday)
    {
        return $this->holidayRepo->delete([$holiday->id]);
    }
}
