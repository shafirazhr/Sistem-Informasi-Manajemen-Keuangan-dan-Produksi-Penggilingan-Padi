@extends('layoutsPU.app')

@section('content')

<h3>Tambah Transaksi Pembelian Padi</h3>

<form action="{{ route('pgabahs.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label>Tanggal Transaksi</label>
        <input type="date" name="tanggal" class="form-control">
    </div>

    <div class="mb-3">
        <label>Nama Pemasok</label>
        <input type="text" name="nama_pemasok" class="form-control">
    </div>

    <div class="mb-3">
        <label>Jumlah Padi</label>
        <input type="number" name="jumlah_gabah" class="form-control">
    </div>

    <div class="mb-3">
        <label>Harga</label>
        <input type="number" step="0.01" name="harga" class="form-control">
    </div>

    <button class="btn btn-secondary" type="button" onclick="window.location.href='{{ route('pgabahs.index') }}'">Batal</button>
    <button class="btn btn-primary" type="submit">Simpan</button>

</form>

@endsection