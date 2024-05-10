<?php

namespace App\Models;

use App\Enums\Moto\MotoStatus;
use App\Traits\BaseScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Moto extends Model
{
    use HasFactory, BaseScope;
    protected $fillable = [
        'name',
        'license_plate',
        'status',
        'price',
        'moto_type_id',
        'description',
    ];
    protected $with = ['motoType', 'images'];
    protected $perPage = 4;
    protected $casts = [
        'status' => MotoStatus::class,
    ];

    public function motoType(): BelongsTo
    {
        return $this->belongsTo(MotoType::class, 'moto_type_id', 'id');
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'moto_id', 'id');
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_details', 'moto_id', 'order_id');
    }

    public function scopeReadyRent(Builder $query): Builder
    {
        return $query->whereIn(
            'status',
            MotoStatus::readyRent(),
        );
    }
}
