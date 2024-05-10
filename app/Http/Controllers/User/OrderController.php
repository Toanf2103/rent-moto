<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\BaseApiController;
use App\Http\Requests\User\OrderRequest;
use App\Http\Requests\User\OrderUpdateRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\User\OrderService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class OrderController extends BaseApiController
{
    public function __construct(
        protected OrderService $orderSer
    ) {
    }

    public function index(Request $rq)
    {
        try {
            $orders = $this->orderSer->getOrders($rq->all());
            return $this->sendResourceResponse(
                OrderResource::collection($orders)
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError(__('alert.params.invalid'), Response::HTTP_BAD_REQUEST);
        }
    }

    public function store(OrderRequest $rq)
    {
        try {
            $order = $this->orderSer->setRequest($rq)->hanldeCreate();
            return $this->sendResponse(
                OrderResource::make($order)
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->sendError(
                __('alert.moto.rent.has_been_scheduled'),
                Response::HTTP_UNPROCESSABLE_ENTITY,
                $e->errors()
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage());
        }
    }

    public function update(Order $order, OrderUpdateRequest $rq)
    {
        try {
            $order = $this->orderSer->setRequest($rq)->update($order->id);
            return $this->sendResponse([
                'message' => __('alert.update_successful')
            ]);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e);
        }
    }

    public function show(Order $order)
    {
        try {
            return $this->sendResponse(
                OrderResource::make($order->load(['orderDetails', 'orderDetails.moto']))
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e);
        }
    }
}
