<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembeli;

class PembeliController extends Controller
{
    public function index()
    {
        $pembelis = Pembeli::latest()->get();
        return view('pages.pembeli.index', compact('pembelis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pembeli' => 'required|string|max:100',
            'alamat' => 'nullable|string|max:255',
            'no_hp' => [
                'required',
                'regex:/^\+628[0-9]{8,10}$/'
            ]
        ], [
            'nama_pembeli.required' => 'Nama wajib diisi',
            'no_hp.required' => 'Nomor HP wajib diisi',
            'no_hp.regex' => 'Harap tulis nomor telepon dengan format +628xxxxxxxx (12-13 digit)'
        ]);

        Pembeli::create([
            'nama_pembeli' => $request->nama_pembeli,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp
        ]);

        return back()->with('success','Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $pembeli = Pembeli::findOrFail($id);
        return view('pages.pembeli.edit', compact('pembeli'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pembeli' => 'required|string|max:100',
            'alamat' => 'nullable|string|max:255',
            'no_hp' => [
                'required',
                'regex:/^\+628[0-9]{8,10}$/'
            ]
        ], [
            'nama_pembeli.required' => 'Nama wajib diisi',
            'no_hp.required' => 'Nomor HP wajib diisi',
            'no_hp.regex' => 'Harap tulis nomor telepon dengan format +628xxxxxxxx (12-13 digit)'
        ]);

        $pembeli = Pembeli::findOrFail($id);

        $pembeli->update([
            'nama_pembeli' => $request->nama_pembeli,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp
        ]);

        return redirect()->route('pembeli.index')
            ->with('success','Data berhasil diupdate');
    }

    public function destroy($id)
    {
        Pembeli::findOrFail($id)->delete();

        return back()->with('success','Data berhasil dihapus');
    }
}