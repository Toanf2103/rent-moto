<?php

namespace App\Services\User;

use App\Models\Moto;
use App\Repositories\Moto\MotoRepositoryInterface;
use App\Services\BaseService;

class MotoService extends BaseService
{
    public function __construct(protected MotoRepositoryInterface $motoRepo)
    {
        //
    }

    public function paginate($filters)
    {
        return $this->motoRepo->paginate($filters, $admin = false);
    }

    public function detail(Moto $moto)
    {
        return $this->motoRepo->detail($moto);
    }

    public function checkRejectedMotos($data)
    {
        return $this->motoRepo->checkRejectedMotos(
            $data['motos'],
            $data['start_date'],
            $data['end_date']
        );
    }
}
