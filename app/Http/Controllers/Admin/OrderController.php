<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Admin\OrderComplete;
use App\Http\Requests\Admin\OrderDenyRequest;
use App\Http\Requests\Admin\OrderUpdateRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\Admin\OrderService;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class OrderController extends BaseApiController
{
    public function __construct(
        protected OrderService $orderSer,
        protected TransactionService $transactinSer
    ) {
        //
    }

    public function index(Request $rq)
    {
        try {
            $orders = $this->orderSer->getList($rq->all());
            return $this->sendResourceResponse(
                OrderResource::collection($orders)
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError(__('alert.params.invalid'), Response::HTTP_BAD_REQUEST);
        }
    }

    public function show(Order $order)
    {
        try {
            $order = $this->orderSer->show($order);
            return $this->sendResponse(
                OrderResource::make($order)
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage());
        }
    }

    public function update(Order $order, OrderUpdateRequest $rq)
    {
        try {
            $this->orderSer->setRequest($rq)->update($order->id);
            return $this->sendResponse([
                'message' => __('alert.update_successful')
            ]);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage());
        }
    }

    public function depositPayment(Order $order)
    {
        try {
            $this->orderSer->handleDepositPayment($order);
            return $this->sendResponse([
                'message' => __('alert.update_successful')
            ]);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage());
        }
    }

    public function complete(Order $order, OrderComplete $rq)
    {
        try {
            $this->orderSer->setRequest($rq)->handleOrderComplete($order);
            return $this->sendResponse([
                'message' => __('alert.update_successful')
            ]);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage());
        }
    }

    public function denyOrder(Order $order, OrderDenyRequest $rq)
    {
        try {
            $order = $this->orderSer->setRequest($rq)->handleDenyOrder($order);
            return $this->sendResponse([
                'message' => __('alert.update_successful')
            ]);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage());
        }
    }
}
