@extends('layoutsadmin.app')

@section('content')

<h3>Pengeluaran Lainnya</h3>


<div class="alert alert-info">
    Data ini digunakan untuk mencatat pengeluaran selain pembelian padi dan penggajian
</div>

<a href="{{ route('pengeluaran.create') }}" class="btn btn-primary mb-3">
    Tambah Pengeluaran
</a>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<form method="GET" action="{{ route('search') }}" class="row mb-3 align-items-end">

    <input type="hidden" name="from" value="pengeluaran">

    <div class="col-md-3">
        <input type="date" name="dari" class="form-control" 
            value="{{ request('dari') }}">
    </div>

    <div class="col-md-1 text-center">
        <small>s/d</small>
    </div>

    <div class="col-md-3">
        <input type="date" name="sampai" class="form-control"
               value="{{ request('sampai') }}">
    </div>

    <div class="col-md-2">
        <select name="sort" class="form-control">
            <option value="terbaru" {{ request('sort')=='terbaru'?'selected':'' }}>Terbaru</option>
            <option value="terlama" {{ request('sort')=='terlama'?'selected':'' }}>Terlama</option>
        </select>
    </div>

    <div class="col-md-3 d-flex gap-2">
        <button type="submit" class="btn btn-primary w-50">Filter</button>
        <a href="{{ route('pengeluaran.index') }}" class="btn btn-secondary w-50">Reset</a>
    </div>

</form>
<div class="table-responsive">  
<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Keterangan</th>
            <th>Total</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>

        @forelse($pengeluarans as $item)

        @php
            $split = explode('-', $item->keterangan);
            $nama = $split[1] ?? '-';
        @endphp

        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->tanggal }}</td>
            <td>{{ ucfirst($nama) }}</td>
            <td>Rp {{ number_format($item->total_pengeluaran,0,',','.') }}</td>
            <td>
                <form action="{{ route('pengeluaran.destroy', $item->id_pengeluaran) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm"
                        onclick="return confirm('Yakin ingin menghapus data ini?')">
                        Hapus
                    </button>
                </form>
            </td>
        </tr>

        @empty
        <tr>
            <td colspan="5" class="text-center">Belum ada data pengeluaran</td>
        </tr>
        @endforelse

    </tbody>
</table>
</div>
@endsection