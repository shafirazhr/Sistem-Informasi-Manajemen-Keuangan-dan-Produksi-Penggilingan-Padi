<?php

namespace App\Http\Controllers;

use App\Models\Pgabah;
use App\Models\Produk;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PgabahsController extends Controller
{
    public function index(Request $request)
{
    $query = Pgabah::query();

    if ($request->dari && $request->sampai && $request->dari > $request->sampai) {
        return back()->with('error', 'Tanggal tidak valid');
    }

    if (!empty($request->dari)) {
        $query->whereDate('tanggal', '>=', $request->dari);
    }

    if (!empty($request->sampai)) {
        $query->whereDate('tanggal', '<=', $request->sampai);
    }

    if ($request->sort === 'terlama') {
        $query->orderBy('tanggal', 'asc');
    } else {
        $query->orderBy('tanggal', 'desc'); // default terbaru
    }

    $pgabahs = $query->get();

    $produkPadi = Produk::find(1);
    $stokPadi = $produkPadi ? $produkPadi->stock : 0;

    return view('pages.pgabahs.index', compact('pgabahs', 'stokPadi'));
}

    public function create()
    {
        return view('pages.pgabahs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'nama_pemasok' => 'required|string|max:100',
            'jumlah_gabah' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated) {

            $validated['produk_id'] = 1;

            $pgabah = Pgabah::create($validated);

            $produk = Produk::findOrFail(1);
            $produk->increment('stock', $validated['jumlah_gabah']);

            Pengeluaran::create([
                'tanggal' => $pgabah->tanggal,
                'total_pengeluaran' => $pgabah->harga * $pgabah->jumlah_gabah,
                'keterangan' => 'pembelianpadi-' . $pgabah->nama_pemasok
            ]);
        });

        return redirect()->route('pgabahs.index')
            ->with('success', 'Transaksi berhasil & stok bertambah');
    }

    public function edit($id)
    {
        $pgabah = Pgabah::findOrFail($id);

        return view('pages.pgabahs.edit', compact('pgabah'));
    }

    public function update(Request $request, $id)
    {
        $pgabah = Pgabah::findOrFail($id);

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'nama_pemasok' => 'required|string|max:100',
            'jumlah_gabah' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($pgabah, $validated) {

            $produk = Produk::findOrFail(1);

            $produk->decrement('stock', $pgabah->jumlah_gabah);

            $pgabah->update([
                'produk_id' => 1,
                'tanggal' => $validated['tanggal'],
                'nama_pemasok' => $validated['nama_pemasok'],
                'jumlah_gabah' => $validated['jumlah_gabah'],
                'harga' => $validated['harga'],
            ]);

            $produk->increment('stock', $validated['jumlah_gabah']);

            // Update pengeluaran (pakai keterangan lama)
            Pengeluaran::where('keterangan', 'like', 'pembelianpadi-%')
                ->where('tanggal', $pgabah->tanggal)
                ->update([
                    'tanggal' => $validated['tanggal'],
                    'total_pengeluaran' => $validated['harga'] * $validated['jumlah_gabah'],
                    'keterangan' => 'pembelianpadi-' . $validated['nama_pemasok']
                ]);
        });

        return redirect()->route('pgabahs.index')
            ->with('success', 'Transaksi diperbarui & stok disesuaikan');
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {

            $pgabah = Pgabah::findOrFail($id);

            $produk = Produk::findOrFail(1);

            $produk->decrement('stock', $pgabah->jumlah_gabah);

            Pengeluaran::where('keterangan', 'like', 'pembelianpadi-%')
                ->where('tanggal', $pgabah->tanggal)
                ->delete();

            $pgabah->delete();
        });

        return redirect()->route('pgabahs.index')
            ->with('success', 'Transaksi dihapus & stok berkurang');
    }
}
