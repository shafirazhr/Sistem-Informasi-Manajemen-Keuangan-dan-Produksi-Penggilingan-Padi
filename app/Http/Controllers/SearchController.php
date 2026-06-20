<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pgabah;
use App\Models\Pengeluaran;
use App\Models\Produk;
use App\Models\Penjualan;
use App\Models\Penggilingan;
use App\Models\Pembeli;
Use App\Models\Penggajian;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        if(!session('login')){
            return redirect('/login');
        }

        $q = $request->q;
        $from = $request->from;

        if($from == 'penggajian'){
            $data = \App\Models\Pengeluaran::where('keterangan','like','penggajian-%')
                ->where('keterangan','like',"%$q%")
                ->get();

            return view('pages.penggajian.index', [
                'penggajians' => $data,
                'q' => $q
            ]);
        }

        if($from == 'pengeluaran'){

            $query = \App\Models\Pengeluaran::where('keterangan','like','pengeluaran-%');

            if ($request->dari && $request->sampai && $request->dari > $request->sampai) {
                return back()->with('error', 'Tanggal tidak valid');
            }

            if ($request->dari) {
                $query->whereDate('tanggal', '>=', $request->dari);
            }

            if ($request->sampai) {
                $query->whereDate('tanggal', '<=', $request->sampai);
            }

            if ($q) {
                $query->where(function($x) use ($q){
                    $x->where('keterangan','like',"%$q%")
                    ->orWhere('tanggal','like',"%$q%");
                });
            }
            if ($request->sort === 'terlama') {
                $query->orderBy('tanggal','asc');
            } else {
                $query->orderBy('tanggal','desc');
            }

            $data = $query->get();

            return view('pages.pengeluaran.index', [
                'pengeluarans' => $data,
                'q' => $q
            ]);
        }

        if($from == 'pgabahs'){
            $data = \App\Models\Pgabah::where('nama_pemasok','like',"%$q%")->get();

            $stokPadi = \App\Models\Produk::find(1)->stock ?? 0;

            return view('pages.pgabahs.index', [
                'pgabahs' => $data,
                'stokPadi' => $stokPadi,
                'q' => $q
            ]);
        }

        if($from == 'penjualan'){
            $data = \App\Models\Penjualan::leftJoin('pembelis','penjualans.pembeli_id','=','pembelis.id')
                ->leftJoin('detail_penjualans','penjualans.id','=','detail_penjualans.penjualan_id')
                ->leftJoin('produks','detail_penjualans.produk_id','=','produks.id')
                ->where(function($query) use ($q){

                    $query->where('produks.nama_produk','like',"%$q%")
                        ->orWhere('penjualans.tanggal','like',"%$q%")
                        ->orWhere('pembelis.nama_pembeli','like',"%$q%")

                        ->orWhere(function($q2) use ($q){
                            $q2->whereNull('pembelis.nama_pembeli') // pembeli umum
                            ->where('produks.nama_produk','like',"%$q%");
                        });

                })
                ->select('penjualans.*')
                ->distinct()
                ->get();

            return view('pages.penjualan.index', [
                'penjualans' => $data,
                'q' => $q
            ]);
        }

        if($from == 'penggilingan'){
            $data = \App\Models\Penggilingan::where('tanggal','like',"%$q%")->get();

            return view('pages.penggilingan.index', [
                'penggilingans' => $data,
                'q' => $q
            ]);
        }

        if($from == 'pembeli'){
            $data = \App\Models\Pembeli::where('nama_pembeli','like',"%$q%")->get();

            return view('pages.pembeli.index', [
                'pembelis' => $data,
                'q' => $q
            ]);
        }
        
        if($from == 'laporan_penjualan'){

            $penjualans = Penjualan::with(['pembeli','details.produk'])
                ->where(function($query) use ($q){
                    $query->where('tanggal','like',"%$q%")
                        ->orWhereHas('pembeli', function($q2) use ($q){
                            $q2->where('nama_pembeli','like',"%$q%");
                        })
                        ->orWhereHas('details', function($q3) use ($q){
                            $q3->whereHas('produk', function($q4) use ($q){
                                $q4->where('nama_produk','like',"%$q%");
                            });
                        });
                })
                ->latest()
                ->get();

            $produks = Produk::all();

            return view('pages.laporan.penjualan', [
                'penjualans' => $penjualans,
                'produks' => $produks,
                'q' => $q
            ]);
        }

if($from == 'laporan_pengeluaran'){

    $pengeluarans = Pengeluaran::where(function($query) use ($q){
        $query->where('tanggal','like',"%$q%")
              ->orWhere('keterangan','like',"%$q%");
    })
    ->orderBy('tanggal','desc')
    ->get();

    return view('pages.laporan.pengeluaran', [
        'pengeluarans' => $pengeluarans,
        'q' => $q
    ]);
}

        if($from == 'laporan_keseluruhan'){

            $penjualan = Penjualan::where('tanggal','like',"%$q%")->get();
            $pengeluaran = Pengeluaran::where('keterangan','like',"%$q%")->get();

            return view('pages.laporan.keseluruhan', [
                'penjualans' => $penjualan,
                'pengeluarans' => $pengeluaran,
                'q' => $q
            ]);
        }
        return back()->with('error','Halaman tidak dikenali');
    }
}