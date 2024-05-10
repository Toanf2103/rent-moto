<?php

namespace App\Services\User;

use App\Models\Order;
use App\Repositories\Moto\MotoRepositoryInterface;
use App\Repositories\OrderDetail\OrderDetailRepositoryInterface;
use App\Services\BaseService;
use App\Services\CalculateUtil;

class OrderDetailService extends BaseService
{
    public function __construct(
        protected OrderDetailRepositoryInterface $orderDetailRep,
        protected MotoRepositoryInterface $motoRep,
        protected CalculateUtil $caculateUtil
    ) {
        //
    }

    public function create($data, Order $order)
    {
        $motos = $this->motoRep->getPriceByIds($data['motos']);
        $data = [];
        $createdAt = now();
        foreach ($motos as $id => $price) {
            $data[] = [
                'order_id' => $order->id,
                'moto_id' => $id,
                'price' => $price,
                'total_pay' => $this->caculateUtil->calculatePriceTotalPay($price, $order),
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];
        }
        return $this->orderDetailRep->insert($data);
    }
}
