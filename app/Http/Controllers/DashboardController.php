<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\Pengeluaran;
use App\Models\Produk;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function pemilik(Request $request)
    {
        return $this->getData('pages.dashboardPU', $request);
    }

    public function admin(Request $request)
    {
        return $this->getData('pages.dashboardAdmin', $request);
    }

    private function getData($view, $request)
    {
    $start = Carbon::now()->subMonths(11)->startOfMonth()->format('Y-m-d');
    $end = Carbon::now()->endOfMonth()->format('Y-m-d');

    $penjualanData = Penjualan::select(
            DB::raw('DATE_FORMAT(tanggal, "%Y-%m") as bulan'),
            DB::raw('SUM(total_harga) as total')
        )
        ->whereBetween('tanggal', [$start, $end])
        ->groupBy('bulan')
        ->get()
        ->keyBy('bulan');

    $pengeluaranData = Pengeluaran::select(
            DB::raw('DATE_FORMAT(tanggal, "%Y-%m") as bulan'),
            DB::raw('SUM(total_pengeluaran) as total')
        )
        ->whereBetween('tanggal', [$start, $end])
        ->groupBy('bulan')
        ->get()
        ->keyBy('bulan');

    $labels = [];
    $dataPemasukan = [];
    $dataPengeluaran = [];

    for ($i = 0; $i < 12; $i++) {
        $date = Carbon::now()->subMonths(11 - $i)->format('Y-m');

        $pemasukan = $penjualanData[$date]->total ?? 0;
        $pengeluaran = $pengeluaranData[$date]->total ?? 0;

        $labels[] = $date;
        $dataPemasukan[] = $pemasukan;
        $dataPengeluaran[] = $pengeluaran;

        }

        $start = Carbon::now()->subDays(30);

        $penjualan = Penjualan::selectRaw('DATE(tanggal) as tanggal, SUM(total_harga) as total')
            ->where('tanggal', '>=', $start)
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        $tanggal = $penjualan->pluck('tanggal');
        $totalPenjualan = $penjualan->pluck('total');

        $padi = Produk::find(1)->stock ?? 0;
        $beras = Produk::find(2)->stock ?? 0;
        $bekatul = Produk::find(3)->stock ?? 0;
        $sekam = Produk::find(4)->stock ?? 0;

        $harian = Penjualan::query();

        if ($request->dari_harian) {
            $harian->whereDate('tanggal', '>=', $request->dari_harian);
        }

        if ($request->sampai_harian) {
            $harian->whereDate('tanggal', '<=', $request->sampai_harian);
        }

        if (!$request->dari_harian && !$request->sampai_harian) {
            $harian->where('tanggal', '>=', now()->subDays(30));
        }

        $laporanHarian = $harian->selectRaw('DATE(tanggal) as tanggal, SUM(total_harga) as total')
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'desc')
            ->get();

        $bulanan = Penjualan::query();

        if ($request->dari_bulan && $request->sampai_bulan) {
            $bulanan->whereBetween('tanggal', [
                $request->dari_bulan . '-01',
                date('Y-m-t', strtotime($request->sampai_bulan . '-01'))
            ]);
        } else {
            $bulanan->where('tanggal', '>=', now()->subMonths(12));
        }

        $laporanBulanan = $bulanan->selectRaw('DATE_FORMAT(tanggal, "%Y-%m") as bulan, SUM(total_harga) as total')
            ->groupBy('bulan')
            ->orderBy('bulan', 'desc')
            ->get();

       
        return view($view, compact(
            'labels' ,
            'dataPemasukan',
            'dataPengeluaran',
            'pemasukan',
            'pengeluaran',
            'tanggal',
            'totalPenjualan',
            'padi',
            'beras',
            'bekatul',
            'sekam',
            'laporanHarian',
            'laporanBulanan'
        ));
    }
}