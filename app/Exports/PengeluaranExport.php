<?php

namespace App\Exports;

use App\Models\Pengeluaran;
use Maatwebsite\Excel\Concerns\FromCollection;

class PengeluaranExport implements FromCollection
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Pengeluaran::query();

        if($this->request->bulan){
            [$tahun, $bulan] = explode('-', $this->request->bulan);

            $query->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $bulan);
        }

        if($this->request->kategori){
            $query->where('keterangan','like',$this->request->kategori.'-%');
        }

        if($this->request->dari){
            $query->whereDate('tanggal','>=',$this->request->dari);
        }

        if($this->request->sampai){
            $query->whereDate('tanggal','<=',$this->request->sampai);
        }

        return $query->get();
    }
}