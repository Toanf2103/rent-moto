<?php

namespace App\Repositories\Order;

use App\Models\Order;
use App\Repositories\RepositoryInterface;

interface OrderRepositoryInterface extends RepositoryInterface
{
    /**
     * Get orders by user
     * @param $idUser
     * @param $filters
     * @return mixed
     */
    public function getOrdersByUser($idUser, $filters);

    /**
     * Get list
     * @param $filters
     * @return mixed
     */
    public function getList($filters);

    /**
     * Get order expired
     * @return mixed
     */
    public function getExpiredOrders();

    /**
     * Deny order
     * @param $order
     * @param $reasonDeny
     * @return mixed
     */
    public function denyOrder(Order $order, $reasonDeny);

    /**
     * Get deposit cost of order
     * @param $order
     * @return mixed
     */
    public function getDepositCost(Order $order);

    /**
     * Get quantity order issue by moto id
     * @param $motoId
     * @return mixed
     */
    public function getQuantityOrderIssue($motoId);

    /**
     * Show detail order
     * @param $order
     * @return mixed
     */
    public function show(Order $order);
}
