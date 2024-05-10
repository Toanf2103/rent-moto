<?php

namespace App\Services\Admin;

use App\Enums\Order\OrderStatus;
use App\Enums\Transaction\TransactionType;
use App\Models\Order;
use App\Notifications\SendMailCancelOrderExpired;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\Transaction\TransactionRepositoryInterface;
use App\Services\BaseService;
use App\Services\TransactionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Throwable;

class OrderService extends BaseService
{
    public function __construct(
        protected OrderRepositoryInterface $orderRep,
        protected TransactionRepositoryInterface $transactionRep,
        protected TransactionService $transactionSer
    ) {
        //
    }
    public function getList($rq)
    {
        return $this->orderRep->getList($rq);
    }

    public function update($orderId)
    {
        return $this->orderRep->update($orderId, $this->data);
    }

    public function cancelExpiredOrders()
    {
        try {
            $order = $this->orderRep->getExpiredOrders();
            if ($order->count() > 0) {
                $this->orderRep->updateMany($order->pluck('id'), [
                    'status' => OrderStatus::CANCEL
                ]);
                $order->load('user');
                collect($order)->each(function ($item) {
                    Notification::send($item->user, new SendMailCancelOrderExpired($item));
                });
                Log::channel('slack')->info("There are {$order->count()} order cancel by cron job");
            }
        } catch (Throwable $e) {
            Log::error($e);
            Log::channel('slack')->error("Cron job cancel order error: " . $e->getMessage());
        }
        return;
    }

    public function approve($orderId)
    {
        return $this->orderRep->update($orderId, [
            'status' => OrderStatus::APPROVE, 'employee_confirm_id' => auth('api')->user()->id
        ]);
    }

    public function complete($orderId)
    {
        return $this->orderRep->update($orderId, ['status' => OrderStatus::COMPLETE, 'date_complete' => now()]);
    }

    public function handleDepositPayment(Order $order)
    {
        DB::beginTransaction();
        try {
            $this->approve($order->id);
            $this->transactionRep->confirmPaymentDeposit($order->id);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function handleOrderComplete(Order $order)
    {
        DB::beginTransaction();
        try {
            if (isset($this->data['incurred_cost'])) {
                $data = [
                    'cost' => $this->data['incurred_cost'],
                    'descriptions' => $this->data['incurred_description'],
                ];
                $this->transactionRep->updateTransaction(TransactionType::INCURRED, $order->id, $data);
            }
            if (isset($this->data['discount_cost'])) {
                $data = [
                    'cost' => $this->data['discount_cost'],
                    'descriptions' => $this->data['discount_description'],
                ];
                $this->transactionRep->updateTransaction(TransactionType::DISCOUNT, $order->id, $data);
            }
            $this->complete($order->id);
            $this->transactionRep->complete($order->id);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function handleDenyOrder(Order $order)
    {
        DB::beginTransaction();
        try {
            if ($this->data['refund_money'] == true && $order->status == OrderStatus::APPROVE) {
                $this->transactionSer->refundMoney($order, $this->data['reason_deny']);
            }
            $this->denyOrder($order);
            DB::commit();
            return;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function denyOrder(Order $order)
    {
        return $this->orderRep->denyOrder($order, $this->data['reason_deny']);
    }

    public function show(Order $order)
    {
        return $this->orderRep->show($order);
    }
}
