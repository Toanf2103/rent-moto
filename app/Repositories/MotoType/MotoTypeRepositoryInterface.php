<?php

namespace App\Repositories\MotoType;


use App\Repositories\RepositoryInterface;

interface MotoTypeRepositoryInterface extends RepositoryInterface
{
    /**
     * Get MotoTypes status public
     * @return mixed
     */
    public function getPublicList();
    
    /**
     * Paginate
     * @param $perPage
     * @return mixed
     */
    public function paginate($perPage = null);

    /**
     * Get number of "motos" for each "motoType"
     * @param $filters
     * @return mixed
     */
    public function getMotoTypeStatistics($filters);

    /**
     * Get moto type with the top revenue
     * @param $filters
     * @return mixed
     */
    public function getTopMotoTypesByRevenue($filters);
}
