<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class KeseluruhanExport implements FromArray
{
    protected $request;

   public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $data = [];

        // PENJUALAN
        $penjualanQuery = Penjualan::with(['pembeli','details.produk']);

        if($this->request->bulan){
            [$tahun, $bulan] = explode('-', $this->request->bulan);

            $penjualanQuery->whereYear('tanggal', $tahun)
                        ->whereMonth('tanggal', $bulan);
        }

        if($this->request->dari){
            $penjualanQuery->whereDate('tanggal','>=',$this->request->dari);
        }

        if($this->request->sampai){
            $penjualanQuery->whereDate('tanggal','<=',$this->request->sampai);
        }

        $penjualans = $penjualanQuery->get();

        foreach($penjualans as $p){
            foreach($p->details as $d){
                $data[] = [
                    'tanggal' => $p->tanggal,
                    'kategori' => 'Penjualan',
                    'keterangan' => $p->pembeli->nama_pembeli ?? 'Umum',
                    'produk' => $d->produk->nama_produk ?? '-',
                    'total' => $p->total_harga
                ];
            }
        }

        // PENGELUARAN
        $pengeluaranQuery = Pengeluaran::query();

        if($this->request->bulan){
            [$tahun, $bulan] = explode('-', $this->request->bulan);

            $pengeluaranQuery->whereYear('tanggal', $tahun)
                            ->whereMonth('tanggal', $bulan);
        }

        if($this->request->dari){
            $pengeluaranQuery->whereDate('tanggal','>=',$this->request->dari);
        }

        if($this->request->sampai){
            $pengeluaranQuery->whereDate('tanggal','<=',$this->request->sampai);
        }

        $pengeluarans = $pengeluaranQuery->get();

        foreach($pengeluarans as $p){
            $data[] = [
                'tanggal' => $p->tanggal,
                'kategori' => 'Pengeluaran',
                'keterangan' => $p->keterangan,
                'produk' => '-',
                'total' => $p->total_pengeluaran
            ];
        }

        return collect($data);
    }
}