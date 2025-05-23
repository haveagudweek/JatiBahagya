<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $role
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Admin extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'role', // super admin', 'staff', 'customer support'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
