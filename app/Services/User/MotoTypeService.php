<?php

namespace App\Services\User;

use App\Repositories\MotoType\MotoTypeRepositoryInterface;
use App\Services\BaseService;
use Illuminate\Support\Facades\Cache;

class MotoTypeService extends BaseService
{
    public function __construct(protected MotoTypeRepositoryInterface $typeRepo)
    {
        //
    }

    public function getAll()
    {
        return Cache::get('public_moto_types', function () {
            return $this->setCachePublic();
        });
    }

    private function setCachePublic()
    {
        $motoTypes = $this->typeRepo->getPublicList();
        Cache::put('public_moto_types', $motoTypes);
        return $motoTypes;
    }
}
