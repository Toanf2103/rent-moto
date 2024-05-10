<?php

namespace App\Repositories\Moto;

use App\Enums\Moto\MotoStatus;
use App\Enums\Order\OrderStatus;
use App\Models\Moto;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class MotoRepository extends BaseRepository implements MotoRepositoryInterface
{
    public function getModel()
    {
        return Moto::class;
    }

    private function baseList($filters)
    {
        return $this->model
            ->when(isset($filters['name']), function ($query) use ($filters) {
                return $query->where('name', 'like', '%' . $filters['name'] . '%');
            })
            ->when(isset($filters['moto_type_id']), function ($query) use ($filters) {
                return $query->where('moto_type_id', $filters['moto_type_id']);
            })
            ->when(isset($filters['min']), function ($query) use ($filters) {
                return $query->where('price', '>=', $filters['min']);
            })
            ->when(isset($filters['max']), function ($query) use ($filters) {
                return $query->where('price', '<=', $filters['max']);
            })
            ->when(isset($filters['license_plate']), function ($query) use ($filters) {
                return $query->where('license_plate', 'like', $filters['license_plate'] . '%');
            })
            ->when(isset($filters['sort']), function ($query) use ($filters) {
                return $query->modelSort($filters['sort']);
            })
            ->when(isset($filters['start_date']) && isset($filters['end_date']), function ($query) use ($filters) {
                return $query->whereDoesntHave('orderDetails.order', function ($query) use ($filters) {
                    return $query
                        ->whereIn('status', OrderStatus::canUpdate())
                        ->checkDate($filters['start_date'], $filters['end_date']);
                });
            });
    }

    public function paginate($filters, $isAdmin = true)
    {
        $motos = $this->baseList($filters);
        $motos = $isAdmin
            ? $motos->when(isset($filters['status']), function ($query) use ($filters) {
                return $query->status($filters['status']);
            })
            :  $motos->readyRent();
        return $motos->paginate($filters['per_page'] ?? null);
    }

    public function detail(Moto $moto)
    {
        return $moto->load(['orders' => function ($query) {
            $query->select('orders.id', 'orders.start_date', 'orders.end_date')
                ->whereIn('status', OrderStatus::canUpdate());
        }]);
    }

    public function getPriceByIds($ids)
    {
        return $this->model->whereIn('id', $ids)->pluck('price', 'id')->toArray();
    }

    public function checkRejectedMotos($idMotos, $startDate, $endDate)
    {
        return $this->model->without(['motoType', 'images'])
            ->whereIn('id', $idMotos)
            ->where(function ($query) use ($startDate, $endDate) {
                return $query
                    ->whereHas('orderDetails.order', function ($query) use ($startDate, $endDate) {
                        return $query
                            ->whereIn('status', OrderStatus::canUpdate())
                            ->checkDate($startDate, $endDate);
                    })
                    ->orWhereNotIn('status', MotoStatus::readyRent());
            })
            ->pluck('id')
            ->toArray();
    }

    public function getList($filters)
    {
        return $this->baseList($filters, $isAdmin = true)->get();
    }

    public function getStatusWithCount($filters)
    {
        return $this->model
            ->select('status', DB::raw('count(status) as quantity'))
            ->without(['motoType', 'images'])
            ->when(isset($filters['moto_type_id']), function ($query) use ($filters) {
                return $query->whereHas('motoType', function ($query) use ($filters) {
                    return $query->whereId($filters['moto_type_id']);
                });
            })
            ->groupBy('status')
            ->get()
            ->toArray();
    }

    public function showMoto(Moto $moto)
    {
        return $moto->load([
            'orders' => function ($query) {
                return $query->select(
                    'orders.id',
                    'orders.user_id',
                    'orders.start_date',
                    'orders.end_date',
                    'orders.id',
                    'orders.status',
                    'orders.user_note'
                );
            },
            'orders.user' => function ($query) {
                return $query->select(
                    'users.id',
                    'users.name'
                );
            }
        ]);
    }

    public function getTopMotosByRevenue($filters)
    {
        return $this->model
            ->select('motos.*', DB::raw('SUM(order_details.total_pay) as revenue'))
            ->join('order_details', 'motos.id', '=', 'order_details.moto_id')
            ->join('orders', 'orders.id', '=', 'order_details.order_id')
            ->where('orders.status', OrderStatus::COMPLETE)
            ->when(isset($filters['year']), function ($query)  use ($filters) {
                return $query->whereYear('orders.date_complete', $filters['year']);
            })
            ->when(isset($filters['month']), function ($query)  use ($filters) {
                return $query->whereMonth('orders.date_complete', $filters['month']);
            })
            ->when(isset($filters['moto_type']), function ($query)  use ($filters) {
                return $query->where('motos.moto_type_id', $filters['moto_type']);
            })
            ->groupBy('motos.id')
            ->orderBy('revenue', 'DESC')
            ->limit($filters['top'] ?? 10)
            ->without(['images', 'motoType'])
            ->get();
    }
}
