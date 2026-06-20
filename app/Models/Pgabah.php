<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pgabah extends Model
{
    protected $table = 'pgabahs';
    protected $guarded = [];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
