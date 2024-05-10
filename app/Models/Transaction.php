<?php

namespace App\Models;

use App\Enums\Transaction\TransactionStatus;
use App\Enums\Transaction\TransactionType;
use App\Traits\BaseScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes, BaseScope;

    protected $fillable = [
        'order_id',
        'type',
        'cost',
        'descriptions',
        'status',
        'date_payment',
    ];
    protected $casts = [
        'status' => TransactionStatus::class,
        'type' => TransactionType::class,
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }


    public function scopeFiltersRevenue(Builder $query, $filters): Builder
    {
        return $query
            ->where('status', TransactionStatus::PAID)
            ->when(isset($filters['customer_id']), function ($query) use ($filters) {
                return $query->whereHas('order', function ($query) use ($filters) {
                    return $query->where('orders.user_id', $filters['customer_id']);
                });
            })
            ->when(isset($filters['rent_package_id']), function ($query) use ($filters) {
                return $query->whereHas('order.rentPackageDetail.rentPackage', function ($query) use ($filters) {
                    return $query->where('rent_packages.id', $filters['rent_package_id']);
                });
            });
    }
}
