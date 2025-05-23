<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'variant_id',
        'quantity',
        'price',
        'options'
    ];

    protected $casts = [
        'options' => 'array'
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relasi ke ProductVariant
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    // Helper untuk mendapatkan nama varian
    public function getVariantNameAttribute()
    {
        return $this->variant ? $this->variant->name : null;
    }

    // Helper untuk mendapatkan gambar
    public function getImageAttribute()
    {
        return $this->variant && $this->variant->image
            ? $this->variant->image
            : ($this->product ? $this->product->main_image : null);
    }
}
