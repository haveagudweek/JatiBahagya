<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_address_id',
        'order_code',
        'total_order',
        'total_shipping',
        'total_fee',
        'amount',
        'status', // 'pending', 'process', 'shipped', 'completed', 'canceled'
        'payment_status', // 'unpaid', 'paid', 'refunded'
        'shipping_address',
        'tracking_number',
        'notes'
    ];

    /**
     * Relasi ke User (satu user bisa memiliki banyak pesanan).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke UserAddress (alamat yang dipilih untuk pengiriman).
     */
    public function userAddress()
    {
        return $this->belongsTo(UserAddress::class);
    }

    /**
     * Relasi ke OrderItems (detail barang yang dibeli dalam pesanan ini).
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relasi ke OrderItems (detail barang yang dibeli dalam pesanan ini).
     */
    public function shippings()
    {
        return $this->hasOne(Shipping::class);
    }

    /**
     * Menghitung total pembayaran.
     */
    public function calculateTotalAmount()
    {
        return $this->total_order + $this->total_shipping + $this->total_fee;
    }
}
