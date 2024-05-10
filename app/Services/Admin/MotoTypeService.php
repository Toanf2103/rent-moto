<?php

namespace App\Services\Admin;

use App\Repositories\MotoType\MotoTypeRepositoryInterface;
use App\Services\BaseService;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MotoTypeService extends BaseService
{
    public function __construct(protected MotoTypeRepositoryInterface $typeRepo)
    {
        //
    }

    public function create()
    {
        $motoType = $this->typeRepo->create($this->data);
        $this->setCacheAll();
        $this->setCachePublic();
        return $motoType;
    }

    public function find($id)
    {
        $type = $this->typeRepo->find($id);
        if (!$type) {
            throw new NotFoundHttpException(__('alert.not_found'));
        }
        return $type;
    }

    public function update($id)
    {
        $this->typeRepo->update($id, $this->data);
        $this->setCacheAll();
        $this->setCachePublic();
        return;
    }

    public function paginate($perPage)
    {
        return $this->typeRepo->paginate($perPage);
    }

    public function all()
    {
        return Cache::get('admin_all_moto_types', function () {
            return $this->setCacheAll();
        });
    }

    public function getPublicList()
    {
        return Cache::get('public_moto_types', function () {
            return $this->setCachePublic();
        });
    }

    private function setCacheAll()
    {
        $motoTypes = $this->typeRepo->all();
        Cache::put('admin_all_moto_types', $motoTypes);
        return $motoTypes;
    }

    private function setCachePublic()
    {
        $motoTypes = $this->typeRepo->getPublicList();
        Cache::put('public_moto_types', $motoTypes);
        return $motoTypes;
    }
}
