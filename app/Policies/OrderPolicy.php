<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function update(User $user, Order $order)
    {
        return $user->id === $order->user_id && $order->canUpdate();
    }

    public function show(User $user, Order $order)
    {
        return $user->id === $order->user_id;
    }

    public function confirmDepositPayment(User $user, Order $order)
    {
        return $order->canUpdate();
    }

    public function deny(User $user, Order $order)
    {
        return $order->canUpdate();
    }
}
