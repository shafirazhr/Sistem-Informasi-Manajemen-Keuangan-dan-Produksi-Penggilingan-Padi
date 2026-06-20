@extends('layoutsadmin.app')

@section('content')

<h3>Tambah Pengeluaran Lainnya</h3>

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('pengeluaran.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label>Tanggal</label>
        <input type="date" name="tanggal" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Nama Pengeluaran</label>
        <input type="text" name="nama_pengeluaran" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Total Pengeluaran</label>
        <input type="number" name="jumlah" class="form-control" required>
    </div>

    <button class="btn btn-success">Simpan</button>
    <a href="{{ route('pengeluaran.index') }}" class="btn btn-secondary">Kembali</a>
</form>

@endsection