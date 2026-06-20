@extends('layoutsadmin.app')

@section('content')

<h3>Data Penggilingan</h3>

<div class="row mb-3">

    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6>Stok Padi</h6>
                <h4>{{ $stokPadi }} Kg</h4>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6>Stok Beras</h6>
                <h4>{{ $stokBeras }} Kg</h4>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <h6>Stok Bekatul</h6>
                <h4>{{ $stokBekatul }} Kg</h4>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-secondary text-white">
            <div class="card-body">
                <h6>Stok Sekam</h6>
                <h4>{{ $stokSekam }} Kg</h4>
            </div>
        </div>
    </div>

</div>

<a href="{{ route('penggilingan.create') }}" class="btn btn-primary mb-3">
    Tambah Penggilingan
</a>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<form method="GET" action="{{ route('penggilingan.index') }}">
<div class="row align-items-end g-2 mb-3">

    <div class="col-md-3">
        <input type="date" name="dari" class="form-control" value="{{ request('dari') }}">
    </div>

    <div class="col-md-1 text-center">
        <label>&nbsp;</label>
        <div>s/d</div>
    </div>

    <div class="col-md-3">
        <input type="date" name="sampai" class="form-control" value="{{ request('sampai') }}">
    </div>

    <div class="col-md-2">
        <select name="sort" class="form-control">
            <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
            <option value="lama" {{ request('sort') == 'lama' ? 'selected' : '' }}>Terlama</option>
        </select>
    </div>

    <div class="col-md-3 d-flex gap-2">
        <button type="submit" class="btn btn-primary w-100">Filter</button>
        <a href="{{ route('penggilingan.index') }}" class="btn btn-secondary w-100">Reset</a>
    </div>

</div>
</form>

<div class="table-responsive">  
<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>Tanggal</th>
            <th>Padi (Kg)</th>
            <th>Beras (65%)</th>
            <th>Bekatul (12%)</th>
            <th>Sekam (23%)</th>
            <th width="150">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($penggilingans as $item)
        <tr>
            <td>{{ $item->tanggal }}</td>
            <td>{{ $item->jumlah_padi }}</td>
            <td>{{ number_format($item->hasil_beras,2) }}</td>
            <td>{{ number_format($item->hasil_bekatul,2) }}</td>
            <td>{{ number_format($item->hasil_sekam,2) }}</td>

            <!-- ✅ AKSI -->
            <td>
                <a href="{{ route('penggilingan.edit', $item->id) }}" 
                   class="btn btn-warning btn-sm">
                    Edit
                </a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center">
                Belum ada data penggilingan
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
</div>

@endsection