<?php

namespace App\Repositories\MotoType;

use App\Enums\Order\OrderStatus;
use App\Models\MotoType;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class MotoTypeRepository extends BaseRepository implements MotoTypeRepositoryInterface
{
    public function getModel()
    {
        return MotoType::class;
    }

    public function getPublicList()
    {
        return $this->model->public()->get();
    }

    public function paginate($perPage = null)
    {
        return $this->model->paginate($perPage);
    }

    public function getMotoTypeStatistics($filters)
    {
        return $this->model->select('name')->withCount('motos')->get()->toArray();
    }

    public function getTopMotoTypesByRevenue($filters)
    {
        return $this->model
            ->select('moto_types.*', DB::raw('SUM(order_details.total_pay) as revenue'))
            ->leftJoin('motos', 'moto_types.id', '=', 'motos.moto_type_id')
            ->leftJoin('order_details', 'motos.id', '=', 'order_details.moto_id')
            ->leftJoin('orders', function ($join) {
                return $join->on('orders.id', '=', 'order_details.order_id')
                    ->where('orders.status', OrderStatus::COMPLETE);
            })
            ->when(isset($filters['year']), function ($query)  use ($filters) {
                return $query->whereYear('orders.date_complete', $filters['year']);
            })
            ->when(isset($filters['month']), function ($query)  use ($filters) {
                return $query->whereMonth('orders.date_complete', $filters['month']);
            })
            ->groupBy('moto_types.id')
            ->orderBy('revenue', 'DESC')
            ->limit($filters['top'] ?? 10)
            ->get();
    }
}
