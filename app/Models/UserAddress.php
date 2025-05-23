<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;

class UserAddress extends Model
{
    use HasFactory;

    protected $table = 'user_addresses';

    protected $fillable = [
        'user_id',
        'id_province',
        'id_regency',
        'id_district',
        'id_village',
        'postal_code',
        'full_address',
    ];

    /**
     * Relasi ke User (Setiap alamat dimiliki oleh satu user)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Province (Setiap alamat memiliki satu provinsi)
     */
    public function province()
    {
        return $this->belongsTo(Province::class, 'id_province');
    }

    /**
     * Relasi ke Regency (Setiap alamat memiliki satu kabupaten/kota)
     */
    public function regency()
    {
        return $this->belongsTo(Regency::class, 'id_regency');
    }

    /**
     * Relasi ke District (Setiap alamat memiliki satu kecamatan)
     */
    public function district()
    {
        return $this->belongsTo(District::class, 'id_district');
    }

    /**
     * Relasi ke Village (Setiap alamat memiliki satu desa/kelurahan)
     */
    public function village()
    {
        return $this->belongsTo(Village::class, 'id_village');
    }
}
