<?php

namespace App\Services\Admin;

use App\Models\OrderDetail;
use App\Repositories\Moto\MotoRepositoryInterface;
use App\Repositories\OrderDetail\OrderDetailRepositoryInterface;
use App\Services\BaseService;
use App\Services\CalculateUtil;
use App\Services\TransactionService;
use Illuminate\Support\Facades\DB;
use Throwable;

class OrderDetailService extends BaseService
{
    public function __construct(
        protected OrderDetailRepositoryInterface $orderDetailRepo,
        protected MotoRepositoryInterface $motoRepo,
        protected TransactionService $transactionSer,
        protected CalculateUtil $caculateUtil
    ) {
        //
    }

    public function hanldeUpdateMoto(OrderDetail $orderDetail)
    {
        DB::beginTransaction();
        try {
            $order = $orderDetail->order;
            $priceMoto = $this->data['price'];
            $totalPay = $this->caculateUtil->calculatePriceTotalPay($priceMoto, $order);
            $this->orderDetailRepo->update(
                $orderDetail->id,
                [
                    'moto_id' => $this->data['moto_id'],
                    'price' => $priceMoto,
                    'total_pay' => $totalPay
                ]
            );
            $order->load('orderDetails');
            $this->transactionSer->updateCostPayment($order);
            DB::commit();
            return;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
