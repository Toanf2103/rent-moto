<?php

namespace App\Models;

use App\Enums\RentPackage\RentPackageStatus;
use App\Traits\BaseScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RentPackage extends Model
{
    use HasFactory, SoftDeletes, BaseScope;
    protected $fillable = [
        'name',
        'status',
    ];
    protected $casts = [
        'status' => RentPackageStatus::class,
    ];

    public function rentPackageDetails(): HasMany
    {
        return $this->hasMany(RentPackageDetail::class, 'rent_package_id', 'id');
    }
}
