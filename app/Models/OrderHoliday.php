<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderHoliday extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'holiday_id',
        'precent',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function holiday(): BelongsTo
    {
        return $this->belongsTo(Holiday::class, 'holiday_id', 'id');
    }
}
