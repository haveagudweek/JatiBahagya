<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 */
class Attribute extends Model
{
    protected $fillable = ['name', 'product_id'];

    public function values()
    {
        return $this->hasMany(AttributeValue::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
