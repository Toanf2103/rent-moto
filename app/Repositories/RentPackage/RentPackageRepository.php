<?php

namespace App\Repositories\RentPackage;

use App\Enums\RentPackage\RentPackageStatus;
use App\Models\RentPackage;
use App\Repositories\BaseRepository;

class RentPackageRepository extends BaseRepository implements RentPackageRepositoryInterface
{
    public function getModel()
    {
        return RentPackage::class;
    }

    public function current()
    {
        return $this->model->where('status', RentPackageStatus::ACTIVE)->first();
    }

    public function getList($perPage)
    {
        return $this->model->with('rentPackageDetails')->paginate($perPage);
    }

    public function activeRentPackage($rentPackage)
    {
        $this->model->status(RentPackageStatus::ACTIVE)->update(['status' => RentPackageStatus::INACTIVE]);
        return $rentPackage->update(['status' => RentPackageStatus::ACTIVE]);
    }
}
