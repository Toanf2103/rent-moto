<?php

namespace App\Services;

use App\Enums\RentPackage\RentPackageStatus;
use App\Models\RentPackage;
use App\Repositories\RentPackage\RentPackageRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Throwable;

class RentPackageService extends BaseService
{
    public function __construct(
        protected RentPackageRepositoryInterface $rentPackageRepo,
        protected RentPackageDetailService $rentPackageDetailService
    ) {
        //    
    }

    public function getList($perPage)
    {
        return $this->rentPackageRepo->getList($perPage);
    }

    public function handleCreateRentPackage()
    {
        DB::beginTransaction();
        try {
            $rentPackage = $this->rentPackageRepo->create([
                'name' => $this->data['name'],
                'status' => RentPackageStatus::INACTIVE
            ]);
            $this->rentPackageDetailService->create($rentPackage->id, $this->data['details']);
            $rentPackage->load('rentPackageDetails');
            DB::commit();
            return $rentPackage;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function active(RentPackage $rentPackage)
    {
        DB::beginTransaction();
        try {
            $this->rentPackageRepo->activeRentPackage($rentPackage);
            DB::commit();
            return;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
