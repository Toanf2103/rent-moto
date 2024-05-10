<?php

namespace App\Services;

use App\Repositories\RentPackageDetail\RentPackageDetailRepositoryInterface;
use Exception;

class RentPackageDetailService extends BaseService
{
    public function __construct(protected RentPackageDetailRepositoryInterface $rentPackageDetailRepo)
    {
        //    
    }

    public function getByDateRent($data)
    {
        $dateRent = diffDate($data['start_date'], $data['end_date']) + 1;
        return $this->rentPackageDetailRepo->getByDateRent($dateRent);
    }

    public function create($packageId, $data)
    {
        $now = now();
        $data = collect($data)->map(function ($item) use ($packageId, $now) {
            return [
                'rent_package_id' => $packageId,
                'percent' => $item['percent'],
                'rent_days_max' => $item['rent_days_max'],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        })->toArray();
        return $this->rentPackageDetailRepo->insert($data);
    }
}
