<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Admin\OrderDetailChangeMotoRequest;
use App\Models\OrderDetail;
use App\Services\Admin\OrderDetailService;
use Illuminate\Support\Facades\Log;
use Throwable;

class OrderDetailController extends BaseApiController
{
    public function __construct(protected OrderDetailService $orderDetailSer)
    {
        //
    }

    public function updateMoto(OrderDetail $orderDetail, OrderDetailChangeMotoRequest $rq)
    {
        try {
            $this->orderDetailSer->setRequest($rq)->hanldeUpdateMoto($orderDetail);
            return $this->sendResponse([
                'message' => __('alert.update_successful')
            ]);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage());
        }
    }
}
