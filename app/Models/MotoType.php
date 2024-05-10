<?php

namespace App\Models;

use App\Enums\MotoType\MotoTypeStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class MotoType extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'moto_types';
    protected $fillable = [
        'name',
        'status'
    ];
    protected $casts = [
        'status' => MotoTypeStatus::class,
    ];

    protected function name(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => Str::lower($value),
            get: fn ($value) => Str::ucfirst($value),
        );
    }

    public function scopePublic(Builder $query): Builder
    {
        return $query->where('status', MotoTypeStatus::ACTIVE);
    }

    public function motos()
    {
        return $this->hasMany(Moto::class, 'moto_type_id', 'id');
    }
}
