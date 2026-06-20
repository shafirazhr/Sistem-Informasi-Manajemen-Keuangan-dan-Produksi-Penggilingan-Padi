<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    protected $guarded = [];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class);
    }
    public function detailproduk()
    {
        return $this->belongsTo(\App\Models\Produk::class, 'produk_id');
    }
}