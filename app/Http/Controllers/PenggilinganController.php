<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penggilingan;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;

class PenggilinganController extends Controller
{
    public function index(Request $request)
    {
        $query = Penggilingan::query();

        if ($request->dari) {
            $query->whereDate('tanggal', '>=', $request->dari);
        }

        if ($request->sampai) {
            $query->whereDate('tanggal', '<=', $request->sampai);
        }

        if ($request->sort == 'lama') {
            $query->orderBy('tanggal', 'asc');
        } else {
            $query->orderBy('tanggal', 'desc');
        }

        $penggilingans = $query->get();

        $stokPadi = Produk::find(1)->stock ?? 0;
        $stokBeras = Produk::find(2)->stock ?? 0;
        $stokBekatul = Produk::find(3)->stock ?? 0;
        $stokSekam = Produk::find(4)->stock ?? 0;

        return view('pages.penggilingan.index', compact(
            'penggilingans',
            'stokPadi',
            'stokBeras',
            'stokBekatul',
            'stokSekam'
        ));
    }

    public function create()
    {
        return view('pages.penggilingan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jumlah_padi' => 'required|numeric|min:1'
        ]);

        $stokPadi = Produk::find(1)->stock ?? 0;

        if ($request->jumlah_padi > $stokPadi) {
            return back()
                ->withInput()
                ->withErrors([
                    'jumlah_padi' => 'Stok padi tidak mencukupi! Stok tersedia hanya ' . $stokPadi . ' kg'
                ]);
        }

        DB::transaction(function () use ($request) {

            $beras = $request->jumlah_padi * 0.65;
            $bekatul = $request->jumlah_padi * 0.12;
            $sekam = $request->jumlah_padi * 0.23;

            Penggilingan::create([
                'tanggal' => $request->tanggal,
                'jumlah_padi' => $request->jumlah_padi,
                'hasil_beras' => $beras,
                'hasil_bekatul' => $bekatul,
                'hasil_sekam' => $sekam
            ]);

            // update stok
            Produk::find(1)->decrement('stock', $request->jumlah_padi);
            Produk::find(2)->increment('stock', $beras);
            Produk::find(3)->increment('stock', $bekatul);
            Produk::find(4)->increment('stock', $sekam);

        });

        return redirect()->route('penggilingan.index')
            ->with('success', 'Penggilingan berhasil & stok diperbarui');
    }

    public function edit($id)
    {
        $penggilingan = Penggilingan::findOrFail($id);
        return view('pages.penggilingan.edit', compact('penggilingan'));
    }

    public function update(Request $request, $id)
    {
        $penggilingan = Penggilingan::findOrFail($id);

        $request->validate([
            'tanggal' => 'required|date',
            'jumlah_padi' => 'required|numeric|min:1'
        ]);

        $stokPadi = Produk::find(1)->stock ?? 0;

        $stokPadiFix = $stokPadi + $penggilingan->jumlah_padi;

        if ($request->jumlah_padi > $stokPadiFix) {
            return back()
                ->withInput()
                ->withErrors([
                    'jumlah_padi' => 'Stok padi tidak mencukupi! Maksimal ' . $stokPadiFix . ' kg'
                ]);
        }

        DB::transaction(function () use ($request, $penggilingan) {

            Produk::find(1)->increment('stock', $penggilingan->jumlah_padi);
            Produk::find(2)->decrement('stock', $penggilingan->hasil_beras);
            Produk::find(3)->decrement('stock', $penggilingan->hasil_bekatul);
            Produk::find(4)->decrement('stock', $penggilingan->hasil_sekam);

            $beras = $request->jumlah_padi * 0.65;
            $bekatul = $request->jumlah_padi * 0.12;
            $sekam = $request->jumlah_padi * 0.23;

            $penggilingan->update([
                'tanggal' => $request->tanggal,
                'jumlah_padi' => $request->jumlah_padi,
                'hasil_beras' => $beras,
                'hasil_bekatul' => $bekatul,
                'hasil_sekam' => $sekam
            ]);

            Produk::find(1)->decrement('stock', $request->jumlah_padi);
            Produk::find(2)->increment('stock', $beras);
            Produk::find(3)->increment('stock', $bekatul);
            Produk::find(4)->increment('stock', $sekam);

        });

        return redirect()->route('penggilingan.index')
            ->with('success', 'Data berhasil diupdate & stok disesuaikan');
    }

    // ✅ OPTIONAL: HAPUS DATA + BALIKIN STOK
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {

            $penggilingan = Penggilingan::findOrFail($id);

            // balikin stok
            Produk::find(1)->increment('stock', $penggilingan->jumlah_padi);
            Produk::find(2)->decrement('stock', $penggilingan->hasil_beras);
            Produk::find(3)->decrement('stock', $penggilingan->hasil_bekatul);
            Produk::find(4)->decrement('stock', $penggilingan->hasil_sekam);

            $penggilingan->delete();
        });

        return redirect()->route('penggilingan.index')
            ->with('success', 'Data berhasil dihapus & stok dikembalikan');
    }
}