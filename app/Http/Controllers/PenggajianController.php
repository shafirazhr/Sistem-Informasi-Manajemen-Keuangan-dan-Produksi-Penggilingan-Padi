<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengeluaran;

class PenggajianController extends Controller
{
    public function index(Request $request)
    {
    $query = Pengeluaran::query();

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
        $penggajians = $query
            ->where('keterangan','like','penggajian-%')
            ->get();

        return view('pages.penggajian.index', compact('penggajians'));
    }

    public function create()
    {
        return view('pages.penggajian.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nama_karyawan' => 'required|string|max:100',
            'jumlah_gaji' => 'required|numeric|min:0'
        ]);

        Pengeluaran::create([
            'tanggal' => $request->tanggal,
            'total_pengeluaran' => $request->jumlah_gaji,
            'keterangan' => 'penggajian-' . strtolower($request->nama_karyawan)
        ]);

        return redirect()->route('penggajian.index')
            ->with('success','Data penggajian berhasil ditambahkan');
    }

    public function destroy($id)
    {
        Pengeluaran::findOrFail($id)->delete();

        return redirect()->route('penggajian.index')
            ->with('success','Data berhasil dihapus');
    }
}