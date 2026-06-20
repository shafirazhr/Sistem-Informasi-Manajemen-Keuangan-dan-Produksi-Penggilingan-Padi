<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\Pengeluaran;
use App\Models\Produk;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PenjualanExport;
use App\Exports\PengeluaranExport;
use App\Exports\KeseluruhanExport;

class LaporanController extends Controller
{
    public function index(Request $request)
{
    $data = [];

    $total_penjualan = 0;
    $total_pengeluaran = 0;

    $penjualanQuery = Penjualan::with(['pembeli','details.produk']);
    $pengeluaranQuery = Pengeluaran::query();

    if($request->bulan){
        [$tahun, $bulan] = explode('-', $request->bulan);

        $penjualanQuery->whereYear('tanggal', $tahun)
                       ->whereMonth('tanggal', $bulan);

        $pengeluaranQuery->whereYear('tanggal', $tahun)
                         ->whereMonth('tanggal', $bulan);
    }

    if($request->dari){
        $penjualanQuery->whereDate('tanggal','>=',$request->dari);
        $pengeluaranQuery->whereDate('tanggal','>=',$request->dari);
    }

    if($request->sampai){
        $penjualanQuery->whereDate('tanggal','<=',$request->sampai);
        $pengeluaranQuery->whereDate('tanggal','<=',$request->sampai);
    }

    $penjualans = $penjualanQuery->get();
    $pengeluarans = $pengeluaranQuery->get();

    foreach($penjualans as $p){

        foreach($p->details as $d){

            $data[] = [
                'tanggal' => $p->tanggal,
                'kategori' => 'Penjualan',
                'keterangan' => $p->pembeli->nama_pembeli ?? 'Umum',
                'produk' => $d->produk->nama_produk ?? '-',
                'berat' => $d->jumlah ?? '-',
                'harga' => $d->harga ?? 0,
                'pembayaran' => $p->pembayaran,
                'total' => $p->total_harga
            ];
        }

        $total_penjualan += $p->total_harga;
    }

    foreach($pengeluarans as $p){

        $split = explode('-', $p->keterangan);
        $kategori = ucfirst($split[0] ?? '-');
        $nama = ucfirst($split[1] ?? '-');

        $data[] = [
            'tanggal' => $p->tanggal,
            'kategori' => $kategori,
            'keterangan' => $nama,
            'produk' => '-',
            'berat' => '-',
            'harga' => '-',
            'pembayaran' => '-',
            'total' => $p->total_pengeluaran
        ];

        $total_pengeluaran += $p->total_pengeluaran;
    }

    usort($data, function ($a, $b) {
        return strtotime($b['tanggal']) - strtotime($a['tanggal']);
    });

    $laba = $total_penjualan - $total_pengeluaran;

    return view('pages.laporan.index', compact(
        'data',
        'total_penjualan',
        'total_pengeluaran',
        'laba'
    ));
}

    public function penjualan(Request $request)
    {
        $query = Penjualan::with('pembeli');

        if($request->q){
            $q = $request->q;

            $query->where(function($x) use ($q){
                $x->where('tanggal','like',"%$q%")
                  ->orWhereHas('pembeli', function($y) use ($q){
                      $y->where('nama_pembeli','like',"%$q%");
                  });
            });
        }

        if($request->bulan){
            $query->whereMonth('tanggal', date('m', strtotime($request->bulan)))
                  ->whereYear('tanggal', date('Y', strtotime($request->bulan)));
        }

        if($request->dari){
            $query->whereDate('tanggal','>=',$request->dari);
        }

        if($request->sampai){
            $query->whereDate('tanggal','<=',$request->sampai);
        }
        if ($request->produk_id) {
        $query->whereHas('details', function ($q) use ($request) {
            $q->where('produk_id', $request->produk_id);
        });
        }
        if ($request->metode_pembayaran) {
        $query->where('metode_pembayaran', $request->metode_pembayaran);
        }

        $penjualans = $query->latest()->get();
        $produks = Produk::all();
        $totalTunai = $penjualans->where('pembayaran','Tunai')->sum('total_harga');
        $totalTransfer = $penjualans->where('pembayaran','Transfer')->sum('total_harga');

        return view('pages.laporan.penjualan', compact(
        'penjualans',
        'produks',
        'totalTunai',
        'totalTransfer'
    ));
    }

    public function penjualanPdf(Request $request)
    {
        $query = Penjualan::with(['pembeli','details.produk']);

        if($request->q){
            $q = $request->q;

            $query->where(function($x) use ($q){
                $x->where('tanggal','like',"%$q%")
                ->orWhereHas('pembeli', function($y) use ($q){
                    $y->where('nama_pembeli','like',"%$q%");
                });
            });
        }

        if($request->bulan){
            $query->whereMonth('tanggal', date('m', strtotime($request->bulan)))
                ->whereYear('tanggal', date('Y', strtotime($request->bulan)));
        }

        if($request->dari){
            $query->whereDate('tanggal','>=',$request->dari);
        }

        if($request->sampai){
            $query->whereDate('tanggal','<=',$request->sampai);
        }

        if ($request->produk_id) {
            $query->whereHas('details', function ($q) use ($request) {
                $q->where('produk_id', $request->produk_id);
            });
        }

        if ($request->metode_pembayaran) {
            $query->where('metode_pembayaran', $request->metode_pembayaran);
        }

        $penjualans = $query->get();

        $pdf = Pdf::loadView('pages.laporan.penjualan_pdf', compact('penjualans'));

        return $pdf->download('laporan_penjualan.pdf');
    }

    public function penjualanExcel(Request $request)
    {
        return Excel::download(new PenjualanExport($request), 'penjualan.xlsx');
    }

    public function pengeluaran(Request $request)
    {
        $query = Pengeluaran::query();

        if($request->bulan){
        [$tahun, $bulan] = explode('-', $request->bulan);

        $query->whereYear('tanggal', $tahun)
              ->whereMonth('tanggal', $bulan);
        }

        if($request->kategori){
            $query->where('keterangan','like',$request->kategori.'-%');
        }

        if($request->dari){
            $query->whereDate('tanggal','>=',$request->dari);
        }

        if($request->sampai){
            $query->whereDate('tanggal','<=',$request->sampai);
        }

        $pengeluarans = $query->orderBy('tanggal','desc')->get();

        return view('pages.laporan.pengeluaran', compact('pengeluarans'));
    }

    public function pengeluaranPdf(Request $request)
    {
        $query = Pengeluaran::query();

        if($request->bulan){
            [$tahun, $bulan] = explode('-', $request->bulan);

            $query->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $bulan);
        }

        if($request->kategori){
            $query->where('keterangan','like',$request->kategori.'-%');
        }

        if($request->dari){
            $query->whereDate('tanggal','>=',$request->dari);
        }

        if($request->sampai){
            $query->whereDate('tanggal','<=',$request->sampai);
        }

        $pengeluarans = $query->orderBy('tanggal','desc')->get();

        $pdf = Pdf::loadView('pages.laporan.pengeluaran_pdf', compact('pengeluarans'));

        return $pdf->download('laporan_pengeluaran.pdf');
    }

    public function pengeluaranExcel(Request $request)
    {
        return Excel::download(new PengeluaranExport($request), 'pengeluaran.xlsx');
    }

    public function keseluruhanPdf(Request $request)
    {
        $data = [];

        $total_penjualan = 0;
        $total_pengeluaran = 0;

        $penjualanQuery = Penjualan::with(['pembeli','details.produk']);

        if($request->bulan){
            [$tahun, $bulan] = explode('-', $request->bulan);

            $penjualanQuery->whereYear('tanggal', $tahun)
                        ->whereMonth('tanggal', $bulan);
        }

        if($request->dari){
            $penjualanQuery->whereDate('tanggal','>=',$request->dari);
        }

        if($request->sampai){
            $penjualanQuery->whereDate('tanggal','<=',$request->sampai);
        }

        $penjualans = $penjualanQuery->get();

        foreach($penjualans as $p){
            foreach($p->details as $d){
                $data[] = [
                    'tanggal' => $p->tanggal,
                    'kategori' => 'Penjualan',
                    'keterangan' => $p->pembeli->nama_pembeli ?? 'Umum',
                    'produk' => $d->produk->nama_produk ?? '-',
                    'berat' => $d->jumlah ?? '-',
                    'harga' => $d->harga ?? 0,
                    'pembayaran' => $p->metode_pembayaran,
                    'total' => $p->total_harga
                ];
            }

            $total_penjualan += $p->total_harga;
        }
        $pengeluaranQuery = Pengeluaran::query();

        if($request->bulan){
            [$tahun, $bulan] = explode('-', $request->bulan);

            $pengeluaranQuery->whereYear('tanggal', $tahun)
                            ->whereMonth('tanggal', $bulan);
        }

        if($request->dari){
            $pengeluaranQuery->whereDate('tanggal','>=',$request->dari);
        }

        if($request->sampai){
            $pengeluaranQuery->whereDate('tanggal','<=',$request->sampai);
        }

        $pengeluarans = $pengeluaranQuery->get();

        foreach($pengeluarans as $p){

            $split = explode('-', $p->keterangan);
            $kategori = ucfirst($split[0] ?? '-');
            $nama = ucfirst($split[1] ?? '-');

            $data[] = [
                'tanggal' => $p->tanggal,
                'kategori' => $kategori,
                'keterangan' => $nama,
                'produk' => '-',
                'berat' => '-',
                'harga' => '-',
                'pembayaran' => '-',
                'total' => $p->total_pengeluaran
            ];

            $total_pengeluaran += $p->total_pengeluaran;
        }

        usort($data, function($a,$b){
            return strtotime($b['tanggal']) - strtotime($a['tanggal']);
        });

        $laba = $total_penjualan - $total_pengeluaran;

        $pdf = Pdf::loadView('pages.laporan.keseluruhan_pdf', compact(
            'data',
            'total_penjualan',
            'total_pengeluaran',
            'laba'
        ));

        return $pdf->download('laporan_keseluruhan.pdf');}
    public function keseluruhanExcel(Request $request)
    {
        return Excel::download(new KeseluruhanExport($request), 'keseluruhan.xlsx');
    }
} 