<?php

namespace App\Models;

use App\Enums\Order\OrderStatus;
use App\Traits\BaseScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Order extends Model
{
    use HasFactory, BaseScope;

    const DEPOSITE_RATE = 0.5;
    const DAY_EXPIRED = 1;
    protected $fillable = [
        'user_id',
        'status',
        'reason_deny',
        'employee_confirm_id',
        'start_date',
        'end_date',
        'date_complete',
        'user_note',
        'phone_number',
        'rent_package_detail_id',
        'rent_package_percent'
    ];
    protected $casts = [
        'status' => OrderStatus::class,
    ];
    protected $perPage = 5;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }

    public function motos(): BelongsToMany
    {
        return $this->belongsToMany(Moto::class, 'order_details', 'order_id', 'moto_id');
    }

    public function rentPackageDetail(): BelongsTo
    {
        return $this->belongsTo(RentPackageDetail::class, 'rent_package_detail_id', 'id');
    }

    public function rentPackage(): BelongsTo
    {
        return $this->rentPackageDetail->rentPackage();
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'order_id', 'id');
    }

    public function scopeCheckDate(Builder $query, $startDate, $endDate): Builder
    {
        return $query->whereBetween('orders.start_date',  [$startDate, $endDate])
            ->orWhereBetween('orders.end_date',  [$startDate, $endDate])
            ->orWhere(function ($query) use ($endDate) {
                $query->where('orders.start_date', '<=', $endDate)
                    ->where('orders.end_date', '>=', $endDate);
            });
    }

    public function canUpdate(): bool
    {
        return in_array($this->status, OrderStatus::canUpdate());
    }

    public function holidays(): HasMany
    {
        return $this->hasMany(OrderHoliday::class, 'order_id', 'id');
    }
}
