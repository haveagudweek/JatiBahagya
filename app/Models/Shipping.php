<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $order_id
 * @property string $courier_name
 * @property string $tracking_number
 * @property string $status
 * @property \Illuminate\Support\Carbon $estimated_delivery_date
 * @property \Illuminate\Support\Carbon|null $delivered_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Shipping extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'courier_name',
        'tracking_number',
        'status',
        'estimated_delivery_date',
        'delivered_at',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
