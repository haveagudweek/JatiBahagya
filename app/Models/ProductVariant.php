<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $product_id
 * @property string $sku
 * @property float $price
 * @property int $stock
 * @property string|null $image
 */
class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'discount',
        'stock',
        'image'
    ];

    protected $casts = [
        'discount' => 'float',
    ];

    public function getFinalPriceAttribute()
    {
        return $this->price - ($this->price * ($this->discount / 100));
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'product_variant_values')
            ->using(ProductVariantValue::class)
            ->withTimestamps()
            ->withPivot('id');
    }
}
