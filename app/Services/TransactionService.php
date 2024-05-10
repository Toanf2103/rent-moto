<?php

namespace App\Services;

use App\Enums\Transaction\TransactionStatus;
use App\Enums\Transaction\TransactionType;
use App\Models\Order;
use App\Notifications\SendMailOrder;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\Transaction\TransactionRepositoryInterface;
use Illuminate\Support\Facades\Notification;

class TransactionService extends BaseService
{
    public function __construct(
        protected TransactionRepositoryInterface $transactionRepo,
        protected OrderRepositoryInterface $orderRepo
    ) {
        //    
    }

    public function create($order)
    {
        $totalAmount = $this->calculateTotalAmount($order);
        $depositeCost = $this->calculateDeposit($totalAmount);
        $createdAt = now();
        $deposit = [
            'type' => TransactionType::DEPOSIT,
            'cost' => $depositeCost
        ];

        $rent = [
            'type' => TransactionType::PAYMENT,
            'cost' => $totalAmount - $depositeCost
        ];

        $incurred = [
            'type' => TransactionType::INCURRED,
            'cost' => 0
        ];

        $discount = [
            'type' => TransactionType::DISCOUNT,
            'cost' => 0
        ];

        $data = [$deposit, $rent, $incurred, $discount];
        $data = collect($data)->map(function ($item) use ($order, $createdAt) {
            $item['order_id'] = $order->id;
            $item['status'] = TransactionStatus::UNPAID;
            $item['created_at'] = $createdAt;
            $item['updated_at'] = $createdAt;
            return $item;
        })->toArray();

        $this->transactionRepo->insert($data);
        Notification::send(auth('api')->user(), new SendMailOrder($order, $depositeCost));
        return;
    }

    public function updateCostPayment(Order $order)
    {
        $totalAmount = $this->calculateTotalAmount($order);
        $depositCost = $this->orderRepo->getDepositCost($order)['cost'];
        $paymentCost = $totalAmount - $depositCost;
        return $this->transactionRepo->updateCostPayment($order->id, $paymentCost);
    }

    public function refundMoney(Order $order, $descriptions)
    {
        return $this->transactionRepo->refundMoney($order->id, $descriptions);
    }

    private function calculateTotalAmount(Order $order)
    {
        return collect($order->orderDetails)->reduce(function ($carry, $item) {
            return $carry + $item->total_pay;
        }, 0);
    }

    private function calculateDeposit($totalAmount)
    {
        return $totalAmount * Order::DEPOSITE_RATE;
    }
}
