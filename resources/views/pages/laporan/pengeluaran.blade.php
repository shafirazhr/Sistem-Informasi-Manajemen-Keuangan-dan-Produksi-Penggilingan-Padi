@extends('layoutsPU.app')

@section('content')

<h3>Laporan Pengeluaran</h3>


<form>
    <div class="col-md-3">
        <input type="date" name="dari" class="form-control"
            value="{{ request('dari') }}">
    </div>

    <div class="col-md-3">
        <input type="date" name="sampai" class="form-control"
            value="{{ request('sampai') }}">
    </div>
{{-- <div class="col-md-3">
        <input type="month" name="bulan" class="form-control"
            value="{{ request('bulan') }}">
    </div> --}}

    <div class="col-md-3">
        <select name="kategori" class="form-control">
            <option value="">Semua</option>
            <option value="pembelianpadi" {{ request('kategori')=='pembelianpadi'?'selected':'' }}>Pembelian Padi</option>
            <option value="penggajian" {{ request('kategori')=='penggajian'?'selected':'' }}>Penggajian</option>
            <option value="pengeluaran" {{ request('kategori')=='pengeluaran'?'selected':'' }}>Pengeluaran Lainnya</option>
        </select>
    </div>

    <div class="col-md-12 mt-2 d-flex gap-2">
        <button class="btn btn-primary">Filter</button>
        <a href="{{ route('laporan.pengeluaran') }}" class="btn btn-secondary">Reset</a>
    </div>
</form>
<div class="table-responsive">  
<table class="table table-bordered">
    <thead>
        <th>Tanggal</th>
        <th>Kategori</th>
        <th>Keterangan</th>
        <th>Total</th>
    </tr>
    </thead>

@php $total = 0; @endphp

@foreach($pengeluarans as $item)

@php
    $split = explode('-', $item->keterangan);
    $kategori = $split[0] ?? '-';
    $nama = $split[1] ?? '-';

    $total += $item->total_pengeluaran;
@endphp

<tr>
    <td>{{ $item->tanggal }}</td>
    <td>{{ ucfirst($kategori) }}</td>
    <td>{{ ucfirst($nama) }}</td>
    <td>Rp {{ number_format($item->total_pengeluaran,0,',','.') }}</td>
</tr>

@endforeach

</table>
</div>
<br>

{{--  --}}


<a href="{{ url('/laporan/pengeluaran/pdf') }}?{{ http_build_query(request()->all()) }}"class="btn btn-danger">PDF</a>
<a href="{{ url('/laporan/pengeluaran/excel') }}?{{ http_build_query(request()->all()) }}"class="btn btn-success">Excel</a>
@endsection