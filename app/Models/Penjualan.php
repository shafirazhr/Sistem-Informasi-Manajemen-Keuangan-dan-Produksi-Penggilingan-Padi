<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $guarded = [];

    public function details()
    {
        return $this->hasMany(DetailPenjualan::class);
    }

    public function pembeli()
    {
        return $this->belongsTo(\App\Models\Pembeli::class, 'pembeli_id');
    }
    public function detailPenjualan()
    {
        return $this->hasMany(\App\Models\DetailPenjualan::class, 'penjualan_id');
    }
}