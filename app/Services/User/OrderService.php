<?php

namespace App\Services\User;

use App\Models\RentPackageDetail;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\RentPackageDetail\RentPackageDetailRepositoryInterface;
use App\Services\BaseService;
use App\Services\RentPackageDetailService;
use Illuminate\Support\Arr;
use App\Services\TransactionService;
use Illuminate\Support\Facades\DB;
use Throwable;

class OrderService extends BaseService
{
    public function __construct(
        protected OrderRepositoryInterface $orderRep,
        protected RentPackageDetailRepositoryInterface $packageDetailRep,
        protected RentPackageDetailService $packageDetailSer,
        protected OrderDetailService $orderDetailSer,
        protected TransactionService $transactionSer,
        protected MotoService $motoSer,
        protected OrderHolidayService $orderHolidaySer
    ) {
        //
    }

    public function create(RentPackageDetail $packetDetail)
    {
        $data = Arr::except($this->data, 'motos');
        $data = [
            ...$data, 'user_id' => auth('api')->user()->id,
            'rent_package_detail_id' => $packetDetail->id,
            'rent_package_percent' => $packetDetail->percent
        ];
        return $this->orderRep->create($data);
    }

    public function update($orderId)
    {
        return $this->orderRep->update($orderId, $this->data);
    }

    public function getOrders($rq)
    {
        return $this->orderRep->getOrdersByUser(auth('api')->user()->id, $rq);
    }

    public function hanldeCreate()
    {
        $rejectedMotos = $this->motoSer->checkRejectedMotos($this->data);
        if (!empty($rejectedMotos)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                "error_motos" => $rejectedMotos
            ]);
        }
        DB::beginTransaction();
        try {
            $packageDetail = $this->packageDetailSer->getByDateRent($this->data);
            $order = $this->create($packageDetail);
            $this->orderHolidaySer->handleCreate($order);
            $order = $order->load('holidays');
            $this->orderDetailSer->create($this->data, $order);
            $order = $order->load(['orderDetails', 'orderDetails.moto', 'orderDetails.moto.images']);
            $this->transactionSer->create($order);
            DB::commit();
            return $order;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
