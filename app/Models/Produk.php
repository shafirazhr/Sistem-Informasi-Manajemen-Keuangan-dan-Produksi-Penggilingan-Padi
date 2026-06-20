<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produks';
    protected $guarded = [];

    public function pgabahs()
    {
        return $this->hasMany(Pgabah::class, 'produk_id');
    }
}