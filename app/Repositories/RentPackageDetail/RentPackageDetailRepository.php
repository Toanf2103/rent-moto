<?php

namespace App\Repositories\RentPackageDetail;

use App\Enums\RentPackage\RentPackageStatus;
use App\Models\RentPackageDetail;
use App\Repositories\BaseRepository;

class RentPackageDetailRepository extends BaseRepository implements RentPackageDetailRepositoryInterface
{
    public function getModel()
    {
        return RentPackageDetail::class;
    }

    public function getByDateRent($dateRent)
    {
        return $this->model->whereHas('rentPackage', function ($query) {
            return $query->status(RentPackageStatus::ACTIVE);
        })
            ->where('rent_days_max', '<=', $dateRent)
            ->orderBy('rent_days_max', 'desc')
            ->first();
    }
}
