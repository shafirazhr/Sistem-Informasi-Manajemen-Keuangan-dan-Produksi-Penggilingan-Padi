<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use App\Models\Pgabah;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = \App\Models\Produk::all();

        $lastBeliPadi = Pgabah::latest()->first();

        $hargaBeliPadi = $lastBeliPadi 
            ? $lastBeliPadi->harga 
            : 0;

        return view('pages.produk.index', compact(
            'produks',
            'hargaBeliPadi'
        ));
    }

    public function create()
    {
        return view('pages.produk.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_produk' => 'required',
            'stock' => 'required|integer|min:0',
            'harga_jual1' => 'required|numeric|min:0',
            'harga_jual2' => 'required|numeric|min:0',
        ]);

        Produk::create($validated);

        return redirect('/produk')->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit($id)
    {
        $produk = Produk::findOrFail($id);

        return view('pages.produk.edit', [
            'produk' => $produk
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_produk' => 'required',
            'stock' => 'required|integer|min:0',
            'harga_jual1' => 'required|numeric|min:0',
            'harga_jual2' => 'required|numeric|min:0',
        ]);

        Produk::findOrFail($id)->update($validated);

        return redirect('/produk')->with('success', 'Produk berhasil diperbarui');
    }
    
}