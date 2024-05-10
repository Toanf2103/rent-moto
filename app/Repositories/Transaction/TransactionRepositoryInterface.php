<?php

namespace App\Repositories\Transaction;

use App\Enums\Transaction\TransactionType;
use App\Repositories\RepositoryInterface;

interface TransactionRepositoryInterface extends RepositoryInterface
{
    /**
     * Confirm payment deposit order
     * @param $orderId
     * @return mixed
     */
    public function confirmPaymentDeposit($orderId);

    /**
     * Complete transaction order
     * @param $orderId
     * @return mixed
     */
    public function complete($orderId);

    /**
     * Retrieve revenue.
     * @param $filters
     * @return mixed
     */
    public function getRevenue($filters);

    /**
     * Get yearly revenue.
     * @param $filters
     * @return mixed
     */
    public function getYearlyRevenue($filters);

    /**
     * Get monthly revenue.
     * @param $filters
     * @return mixed
     */
    public function getMonthlyRevenue($filters);

    /**
     * Update transaction cost payment by order id
     * @param $orderId
     * @param $paymentCost
     * @return mixed
     */
    public function updateCostPayment($orderId, $paymentCost);

    /**
     * Refund money when deny order
     * @param $orderId
     * @param $descriptions
     * @return mixed
     */
    public function refundMoney($orderId, $descriptions);

    /**
     * Update transaction
     * @param TransactionType $type
     * @param $orderId
     * @param $data
     * @return mixed
     */
    public function updateTransaction(TransactionType $type, $orderId, $data);
}
