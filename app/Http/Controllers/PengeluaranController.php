<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengeluaran;

class PengeluaranController extends Controller
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
        $pengeluarans = Pengeluaran::where('keterangan','like','pengeluaran-%')
                        ->latest()
                        ->get();

        return view('pages.pengeluaran.index', compact('pengeluarans'));
    }
    public function create()
    {
        return view('pages.pengeluaran.create');
    }    
    public function store(Request $request)
    {
        $request->validate([
            'tanggal'=>'required|date',
            'nama_pengeluaran'=>'required',
            'jumlah'=>'required|numeric'
        ]);

        Pengeluaran::create([
            'tanggal'=>$request->tanggal,
            'total_pengeluaran'=>$request->jumlah,
            'keterangan'=>'pengeluaran-'.$request->nama_pengeluaran
        ]);

        return redirect()->route('pengeluaran.index')
        ->with('success','Pengeluaran berhasil ditambahkan');
    }
    public function pengeluaran(Request $request)
    {
        $query = \App\Models\Pengeluaran::query();

        if($request->dari){
            $query->whereDate('tanggal','>=',$request->dari);
        }

        if($request->sampai){
            $query->whereDate('tanggal','<=',$request->sampai);
        }

        if($request->urut == 'lama'){
            $query->orderBy('tanggal','asc');
        } else {
            $query->orderBy('tanggal','desc');
        }

        $pengeluarans = $query->get();

        return view('pages.laporan.pengeluaran', compact('pengeluarans'));
    }
    public function destroy($id)
    {
        $data = Pengeluaran::findOrFail($id);
        $data->delete();

        return redirect()->back()->with('success', 'Data pengeluaran berhasil dihapus');
    }
}