<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'moto_id',
        'price',
        'return_date',
        'employee_receive_id',
        'employee_note',
        'type_pay',
        'total_pay',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function moto(): BelongsTo
    {
        return $this->belongsTo(Moto::class, 'moto_id', 'id');
    }
}
