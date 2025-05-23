<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductVariantValue extends Pivot
{
    protected $table = 'product_variant_values';

    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'product_variant_id',
        'attribute_value_id',
    ];
}
