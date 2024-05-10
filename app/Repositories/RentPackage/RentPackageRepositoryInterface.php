<?php

namespace App\Repositories\RentPackage;

use App\Repositories\RepositoryInterface;

interface RentPackageRepositoryInterface extends RepositoryInterface
{
    public function getList($perPage);

    public function activeRentPackage($rentPackage);
}
