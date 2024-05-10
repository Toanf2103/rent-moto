<?php

namespace App\Repositories\Order;

use App\Enums\Moto\MotoStatus;
use App\Enums\Order\OrderStatus;
use App\Enums\Transaction\TransactionStatus;
use App\Enums\Transaction\TransactionType;
use App\Models\Order;
use App\Repositories\BaseRepository;
use Carbon\Carbon;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    public function getModel()
    {
        return Order::class;
    }

    public function getOrdersByUser($idUser, $filters)
    {
        return $this->model
            ->where('user_id', $idUser)
            ->when(isset($filters['status']), function ($query) use ($filters) {
                return $query->status($filters['status']);
            })
            ->when(isset($filters['sort']), function ($query) use ($filters) {
                return $query->modelSort($filters['sort']);
            })
            ->with('orderDetails')
            ->with('user')
            ->with('orderDetails.moto')
            ->with('rentPackageDetail')
            ->with('rentPackageDetail.rentPackage')
            ->paginate($filters['per_page'] ?? null);
    }

    public function getList($filters)
    {
        return $this->model
            ->when(isset($filters['id']), function ($query) use ($filters) {
                return $query->where('id', $filters['id']);
            })
            ->when(isset($filters['phone_number']), function ($query) use ($filters) {
                return $query->where('phone_number', 'like', '%' . $filters['id'] . '%');
            })
            ->when(isset($filters['status']), function ($query) use ($filters) {
                return $query->status($filters['status']);
            })
            ->when(isset($filters['sort']), function ($query) use ($filters) {
                return $query->modelSort($filters['sort']);
            })
            ->when(isset($filters['has_issue']) && $filters['has_issue'] == true, function ($query) {
                return $query->whereIn('status', OrderStatus::canUpdate())
                    ->whereHas('motos', function ($query) {
                        return $query->whereNotIn('status', MotoStatus::readyRent());
                    });
            })
            ->with(['user' => function ($query) {
                return $query->select('users.id', 'users.name');
            }])
            ->with('rentPackageDetail')
            ->with(['motos' => function ($query) {
                return $query->without(['images', 'motoType']);
            }])
            ->with('rentPackageDetail.rentPackage')
            ->paginate($filters['per_page'] ?? null);
    }

    public function getExpiredOrders()
    {
        return $this->model->status(OrderStatus::WAIT)
            ->whereHas('transactions', function ($query) {
                return $query->where('transactions.type', TransactionType::DEPOSIT)
                    ->status(TransactionStatus::UNPAID);
            })
            ->where('created_at', '<', Carbon::now()->subDay(Order::DAY_EXPIRED))
            ->get();
    }

    public function denyOrder(Order $order, $reasonDeny)
    {
        $order->status = OrderStatus::DENY;
        $order->reason_deny = $reasonDeny;
        $order->save();
        return $order;
    }

    public function getDepositCost(Order $order)
    {
        return $order
            ->transactions()
            ->select('cost')
            ->where('type', TransactionType::DEPOSIT)
            ->first()
            ->toArray();
    }

    public function getQuantityOrderIssue($motoId)
    {
        return $this->model
            ->whereIn('status', OrderStatus::canUpdate())
            ->whereHas('motos', function ($query) use ($motoId) {
                return $query->where('motos.id', $motoId)
                    ->whereNotIn('status', MotoStatus::readyRent());
            })
            ->count();
    }

    public function show(Order $order)
    {
        return $order->load([
            'orderDetails', 'transactions', 'orderDetails.moto',
            'rentPackageDetail.rentPackage', 'holidays', 'holidays.holiday'
        ]);
    }
}
