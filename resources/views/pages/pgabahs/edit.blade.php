@extends('layoutsPU.app')

@section('content')

<h3>Edit Transaksi Pembelian</h3>

<form action="{{ route('pgabahs.update',$pgabah->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Tanggal</label>
        <input type="date" name="tanggal"
               value="{{ $pgabah->tanggal }}" class="form-control">
    </div>

    <div class="mb-3">
        <label>Nama Pemasok</label>
        <input type="text" name="nama_pemasok"
               value="{{ $pgabah->nama_pemasok }}" class="form-control">
    </div>

    <div class="mb-3">
        <label>Jumlah Gabah</label>
        <input type="number" name="jumlah_gabah"
               value="{{ $pgabah->jumlah_gabah }}" class="form-control">
    </div>

    <div class="mb-3">
        <label>Harga</label>
        <input type="number" step="0.01" name="harga"
               value="{{ $pgabah->harga }}" class="form-control">
    </div>

    <button class="btn btn-secondary" type="button" onclick="window.location.href='{{ route('pgabahs.index') }}'">Batal</button>
    <button class="btn btn-primary" type="submit">Update</button>

</form>

@endsection