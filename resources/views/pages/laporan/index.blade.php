@extends('layoutsPU.app')

@section('content')

<h3>Laporan Keseluruhan</h3>

<form method="GET" class="row mb-3">

<form method="GET" class="row mb-3">

    {{-- <div class="col-md-3">
        <input type="month" name="bulan" class="form-control"
            value="{{ request('bulan') }}">
    </div> --}}

    <div class="col-md-3">
        <input type="date" name="dari" class="form-control"
            value="{{ request('dari') }}">
    </div>

    <div class="col-md-3">
        <input type="date" name="sampai" class="form-control"
            value="{{ request('sampai') }}">
    </div>

    <div class="col-md-3 d-flex align-items-end gap-2">
        <button class="btn btn-primary">Filter</button>
        <a href="{{ route('laporan.index') }}" class="btn btn-secondary">Reset</a>
    </div>
</form>

<div class="table-responsive">  
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Kategori</th>
            <th>Keterangan</th>
            <th>Produk</th>
            <th>Harga</th>
            <th>Pembayaran</th>
            <th>Total</th>
        </tr>
    </thead>

    <tbody>
        @forelse($data as $item)
        <tr>
            <td>{{ $item['tanggal'] }}</td>
            <td>{{ $item['kategori'] }}</td>
            <td>{{ $item['keterangan'] }}</td>
            <td>{{ $item['produk'] }}</td>

            <td>
                @if(is_numeric($item['harga']))
                    Rp {{ number_format($item['harga'],0,',','.') }}
                @else
                    -
                @endif
            </td>

            <td>{{ $item['pembayaran'] }}</td>

            <td>
                Rp {{ number_format($item['total'],0,',','.') }}
            </td>
        </tr>

        @empty
        <tr>
            <td colspan="8" class="text-center">
                Tidak ada data
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
</div>
<hr>

{{-- <h5>Ringkasan Keuangan</h5>

<div class="card p-3">

    <p>
        <strong>Total Pendapatan:</strong><br>
        Rp {{ number_format($total_penjualan,0,',','.') }}
    </p>

    <p>
        <strong>Total Pengeluaran:</strong><br>
        Rp {{ number_format($total_pengeluaran,0,',','.') }}
    </p>

    <p>
        <strong>Laba / Rugi:</strong><br>
        <span style="color: {{ $laba >= 0 ? 'green' : 'red' }}">
            Rp {{ number_format($laba,0,',','.') }}
        </span>
    </p>

</div> --}}

<div class="mt-3">
    <a href="{{ url('/laporan/keseluruhan/pdf') }}?{{ http_build_query(request()->all()) }}" class="btn btn-danger">PDF</a>
    {{-- <a href="{{ url('/laporan/keseluruhan/excel') }}?{{ http_build_query(request()->all()) }}"class="btn btn-success">Excel</a> --}}
</div>

@endsection