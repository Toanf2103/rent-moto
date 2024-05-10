<?php

namespace App\Repositories\RentPackageDetail;


use App\Repositories\RepositoryInterface;

interface RentPackageDetailRepositoryInterface extends RepositoryInterface
{
    /**
     * Get rent package details by date rent
     * @param $dateRent
     * @return mixed
     */
    public function getByDateRent($dateRent);
}
