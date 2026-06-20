<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembeli extends Model
{
    protected $fillable = [
        'nama_pembeli',
        'alamat',
        'no_hp'
    ];

    public function penjualans()
    {
        return $this->hasMany(Penjualan::class);
    }
}