<?php

namespace App\Models;

use App\Traits\BaseScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RentPackageDetail extends Model
{
    use HasFactory, BaseScope;
    protected $fillable = [
        'rent_package_id',
        'rent_days_max',
        'percent',
    ];

    public function rentPackage(): BelongsTo
    {
        return $this->belongsTo(RentPackage::class, 'rent_package_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'rent_package_detail_id', 'id');
    }
}
