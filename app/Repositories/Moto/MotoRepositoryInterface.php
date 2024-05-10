<?php

namespace App\Repositories\Moto;

use App\Models\Moto;
use App\Repositories\RepositoryInterface;

interface MotoRepositoryInterface extends RepositoryInterface
{
    /**
     * Paginate
     * @param $filters
     * @param $isAdmin
     * @return mixed
     */
    public function paginate($filters, $isAdmin = true);

    /**
     * Show detail moto
     * @param $moto
     * @return mixed
     */
    public function detail(Moto $moto);

    /**
     * Get price motos by ids
     * @param $idMotos
     * @return mixed
     */
    public function getPriceByIds($ids);

    /**
     * Check moto reject by date
     * @param $idMotos
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function checkRejectedMotos($idMotos, $startDate, $endDate);

    /**
     * Get list moto
     * @param $filters
     * @return mixed
     */
    public function getList($filters);

    /**
     * Get number of "motos" for each "status"
     * @param $filters
     * @return mixed
     */
    public function getStatusWithCount($filters);

    /**
     * Show detail of moto
     * @param $filters
     * @return mixed
     */
    public function showMoto(Moto $moto);

    /**
     * Get motos with the top revenue
     * @param $filters
     * @return mixed
     */
    public function getTopMotosByRevenue($filters);
}
