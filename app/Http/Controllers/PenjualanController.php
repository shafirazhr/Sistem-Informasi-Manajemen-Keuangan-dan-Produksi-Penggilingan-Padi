<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Produk;
use App\Models\Pembeli;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    public function index(Request $request)
    {
        $query = Penjualan::with('details.produk','pembeli');

        if($request->dari){
            $query->whereDate('tanggal','>=',$request->dari);
        }

        if($request->sampai){
            $query->whereDate('tanggal','<=',$request->sampai);
        }

        if($request->sort == 'lama'){
            $query->orderBy('tanggal','asc');
        } else {
            $query->orderBy('tanggal','desc');
        }

        $penjualans = $query->get();

        return view('pages.penjualan.index', compact('penjualans'));
    }

    public function create()
    {
        $produks = Produk::all();
        $pembelis = Pembeli::all();

        return view('pages.penjualan.create', compact('produks','pembelis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'produk' => 'required|array',
            'jumlah' => 'required|array',
            'pembayaran' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required'
        ]);

        DB::transaction(function () use ($request){

            $isMember = $request->pembeli_id ? true : false;

            $penjualan = Penjualan::create([
                'tanggal'=>$request->tanggal,
                'pembeli_id'=>$request->pembeli_id,
                'total_harga'=>0,
                'pembayaran'=>0,
                'kembalian'=>0,
                'metode_pembayaran'=>$request->metode_pembayaran
            ]);

            $total = 0;

            foreach($request->produk as $key => $produk_id){

                $produk = Produk::findOrFail($produk_id);
                $jumlah = $request->jumlah[$key];

                if($jumlah <= 0){
                    return back()->withErrors(['jumlah'=>'Jumlah tidak valid']);
                }

                if($produk->stock < $jumlah){
                    return back()->withErrors([
                        'stok' => 'Stok '.$produk->nama_produk.' tidak cukup'
                    ])->withInput();
                }

                $harga = $isMember 
                    ? $produk->harga_jual1 
                    : $produk->harga_jual2;

                $subtotal = $harga * $jumlah;

                DetailPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'produk_id' => $produk_id,
                    'jumlah' => $jumlah,
                    'harga' => $harga
                ]);

                $produk->decrement('stock', $jumlah);

                $total += $subtotal;
            }

            $pembayaran = $request->pembayaran;

            // 🔥 VALIDASI METODE
            if($request->metode_pembayaran == 'tunai'){
                if($pembayaran < $total){
                    return back()->withErrors([
                        'pembayaran' => 'Pembayaran tunai kurang'
                    ])->withInput();
                }
            }

            if($request->metode_pembayaran == 'transfer'){
                if($pembayaran != $total){
                    return back()->withErrors([
                        'pembayaran' => 'Transfer harus sama dengan total'
                    ])->withInput();
                }
            }

            $kembalian = $pembayaran - $total;

            $penjualan->update([
                'total_harga' => $total,
                'pembayaran' => $pembayaran,
                'kembalian' => $kembalian
            ]);
        });

        return redirect()->route('penjualan.index')
            ->with('success','Penjualan berhasil');
    }
    public function edit($id)
    {
        $penjualan = Penjualan::with('details')->findOrFail($id);
        $produks = Produk::all();
        $pembelis = Pembeli::all();

        return view('pages.penjualan.edit', compact('penjualan','produks','pembelis'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'produk' => 'required|array',
            'jumlah' => 'required|array',
            'pembayaran' => 'required|numeric|min:0'
        ]);

        DB::transaction(function () use ($request, $id){

            $penjualan = Penjualan::with('details')->findOrFail($id);

            foreach($penjualan->details as $detail){
                $detail->produk->increment('stock', $detail->jumlah);
            }

            DetailPenjualan::where('penjualan_id',$id)->delete();

            $isMember = $request->pembeli_id ? true : false;

            $total = 0;

            foreach($request->produk as $key => $produk_id){

                $produk = Produk::findOrFail($produk_id);
                $jumlah = $request->jumlah[$key];

                if($produk->stock < $jumlah){
                    abort(400,'Stok tidak cukup');
                }

                $harga = $isMember ? $produk->harga_jual1 : $produk->harga_jual2;

                $subtotal = $harga * $jumlah;

                DetailPenjualan::create([
                    'penjualan_id'=>$id,
                    'produk_id'=>$produk_id,
                    'jumlah'=>$jumlah,
                    'harga'=>$harga
                ]);

                $produk->decrement('stock',$jumlah);

                $total += $subtotal;
            }

            $pembayaran = $request->pembayaran;
            $kembalian = $pembayaran - $total;

            $penjualan->update([
                'tanggal'=>$request->tanggal,
                'pembeli_id'=>$request->pembeli_id,
                'total_harga'=>$total,
                'pembayaran'=>$pembayaran,
                'kembalian'=>$kembalian
            ]);
        });

        return redirect()->route('penjualan.index')
            ->with('success','Data berhasil diupdate');
    }
    public function destroy($id)
    {
        DB::transaction(function () use ($id){

            $penjualan = Penjualan::with('details')->findOrFail($id);

            foreach($penjualan->details as $detail){
                $detail->produk->increment('stock', $detail->jumlah);
            }

            DetailPenjualan::where('penjualan_id',$id)->delete();
            $penjualan->delete();
        });

        return back()->with('success','Data berhasil dihapus');
    }
    public function nota($id)
    {
        $penjualan = Penjualan::with('details.produk','pembeli')->findOrFail($id);

        return view('pages.penjualan.nota', compact('penjualan'));
    }
    public function penjualan(Request $request)
    {
        $query = \App\Models\Penjualan::query();

        // filter tanggal
        if($request->dari){
            $query->whereDate('tanggal','>=',$request->dari);
        }

        if($request->sampai){
            $query->whereDate('tanggal','<=',$request->sampai);
        }

        if($request->urut == 'lama'){
            $query->orderBy('tanggal','asc');
        } else {
            $query->orderBy('tanggal','desc'); // default terbaru
        }

        $penjualans = $query->get();

        return view('pages.laporan.penjualan', compact('penjualans'));
    }
}