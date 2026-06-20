<?php

namespace App\Exports;


use App\Models\Penjualan;
use Maatwebsite\Excel\Concerns\FromCollection;

class PenjualanExport implements FromCollection
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Penjualan::with(['pembeli','details.produk']);

        if($this->request->q){
            $q = $this->request->q;

            $query->where(function($x) use ($q){
                $x->where('tanggal','like',"%$q%")
                ->orWhereHas('pembeli', function($y) use ($q){
                    $y->where('nama_pembeli','like',"%$q%");
                });
            });
        }

        if($this->request->bulan){
            $query->whereMonth('tanggal', date('m', strtotime($this->request->bulan)))
                ->whereYear('tanggal', date('Y', strtotime($this->request->bulan)));
        }

        if($this->request->dari){
            $query->whereDate('tanggal','>=',$this->request->dari);
        }

        if($this->request->sampai){
            $query->whereDate('tanggal','<=',$this->request->sampai);
        }

        if ($this->request->produk_id) {
            $query->whereHas('details', function ($q) {
                $q->where('produk_id', $this->request->produk_id);
            });
        }

        if ($this->request->metode_pembayaran) {
            $query->where('metode_pembayaran', $this->request->metode_pembayaran);
        }

        return $query->get();
    }
}