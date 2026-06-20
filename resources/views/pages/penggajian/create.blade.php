@extends('layoutsPU.app')

@section('content')

<h3>Tambah Data Penggajian</h3>

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif


<form action="{{ route('penggajian.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label>Tanggal</label>
        <input type="date" name="tanggal" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Nama Karyawan</label>
        <input type="text" name="nama_karyawan" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Jumlah Gaji</label>
        <input type="number" name="jumlah_gaji" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-success">Simpan</button>
    <a href="{{ route('penggajian.index') }}" class="btn btn-secondary">Kembali</a>
</form>

@endsection