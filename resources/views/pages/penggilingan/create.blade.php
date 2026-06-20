@extends('layoutsadmin.app')

@section('content')

<h3>Tambah Penggilingan</h3>

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('penggilingan.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label>Tanggal</label>
        <input type="date" name="tanggal" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Jumlah Padi (Kg)</label>
        <input type="number" step="0.01" name="jumlah_padi" id="jumlah_padi" class="form-control" required>
    </div>

    <hr>

    <h5>Estimasi Hasil</h5>

    <div class="mb-2">
        <label>Beras (65%)</label>
        <input type="text" id="beras" class="form-control" readonly>
    </div>

    <div class="mb-2">
        <label>Bekatul (12%)</label>
        <input type="text" id="bekatul" class="form-control" readonly>
    </div>

    <div class="mb-3">
        <label>Sekam (23%)</label>
        <input type="text" id="sekam" class="form-control" readonly>
    </div>

    <button class="btn btn-success">Simpan</button>
    <a href="{{ route('penggilingan.index') }}" class="btn btn-secondary">Kembali</a>
</form>

<script>
document.getElementById('jumlah_padi').addEventListener('input', function(){

    let padi = parseFloat(this.value) || 0;

    let beras = padi * 0.65;
    let bekatul = padi * 0.12;
    let sekam = padi * 0.23;

    document.getElementById('beras').value = beras.toFixed(2);
    document.getElementById('bekatul').value = bekatul.toFixed(2);
    document.getElementById('sekam').value = sekam.toFixed(2);

});
</script>

@endsection