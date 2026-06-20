<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    protected $table = 'pengeluaran';

    protected $primaryKey = 'id_pengeluaran';

    protected $fillable = [
        'tanggal',
        'keterangan',
        'total_pengeluaran'
    ];
}