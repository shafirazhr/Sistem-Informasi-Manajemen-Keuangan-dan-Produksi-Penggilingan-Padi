@extends('layoutsadmin.app')

@section('content')

<h3>Edit Penggilingan</h3>

<a href="{{ route('penggilingan.index') }}" class="btn btn-secondary mb-3">
    ← Kembali
</a>

@if($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('penggilingan.update', $penggilingan->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="card p-4 shadow-sm">

        <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date" name="tanggal" class="form-control"
                   value="{{ old('tanggal', $penggilingan->tanggal) }}" required>
        </div>

        <!-- JUMLAH PADI -->
        <div class="mb-3">
            <label class="form-label">Jumlah Padi (Kg)</label>
            <input type="number" name="jumlah_padi" id="jumlah_padi"
                   class="form-control"
                   value="{{ old('jumlah_padi', $penggilingan->jumlah_padi) }}"
                   min="1" step="0.01" required>
        </div>

        <hr>

        <h5>Hasil (Otomatis)</h5>

        <div class="row">

            <div class="col-md-4 mb-3">
                <label>Beras (65%)</label>
                <input type="text" id="beras" class="form-control" readonly>
            </div>

            <div class="col-md-4 mb-3">
                <label>Bekatul (12%)</label>
                <input type="text" id="bekatul" class="form-control" readonly>
            </div>

            <div class="col-md-4 mb-3">
                <label>Sekam (23%)</label>
                <input type="text" id="sekam" class="form-control" readonly>
            </div>

        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success w-100">
                Update
            </button>
            <button type="reset" class="btn btn-secondary w-100">
                Reset
            </button>
        </div>

    </div>

</form>

<script>
function hitungHasil() {
    let padi = parseFloat(document.getElementById('jumlah_padi').value) || 0;

    let beras = padi * 0.65;
    let bekatul = padi * 0.12;
    let sekam = padi * 0.23;

    document.getElementById('beras').value = beras.toFixed(2) + ' Kg';
    document.getElementById('bekatul').value = bekatul.toFixed(2) + ' Kg';
    document.getElementById('sekam').value = sekam.toFixed(2) + ' Kg';
}

// jalan saat load
hitungHasil();

// jalan saat input berubah
document.getElementById('jumlah_padi').addEventListener('input', hitungHasil);
</script>

@endsection