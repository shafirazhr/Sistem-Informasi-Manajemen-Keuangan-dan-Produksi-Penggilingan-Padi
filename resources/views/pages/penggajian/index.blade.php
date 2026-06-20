@extends('layoutsPU.app')

@section('content')

<h3>Data Penggajian</h3>

<a href="{{ route('penggajian.create') }}" class="btn btn-primary mb-3">
    Tambah Data Penggajian
</a>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<form method="GET" class="row mb-3 align-items-end">

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
        <a href="{{ route('penggajian.index') }}" class="btn btn-secondary w-50">Reset</a>
    </div>

</form>
<div class="table-responsive">  
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Nama Karyawan</th>
            <th>Jumlah Gaji</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>

        @forelse($penggajians as $item)

        @php
            $split = explode('-', $item->keterangan);
            $nama = $split[1] ?? '-';
        @endphp

        <tr>
            <td>{{ $item->tanggal }}</td>
            <td>{{ ucfirst($nama) }}</td>
            <td>Rp {{ number_format($item->total_pengeluaran,0,',','.') }}</td>
            <td>
                @if($item->id_pengeluaran)
                <form action="{{ route('penggajian.destroy', $item->id_pengeluaran) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm"
                        onclick="return confirm('Yakin ingin menghapus data ini?')">
                        Hapus
                    </button>
                </form>
                @else
                <span class="text-danger">ID tidak ditemukan</span>
                @endif
            </td>
        </tr>

        @empty
        <tr>
            <td colspan="4" class="text-center">Belum ada data penggajian</td>
        </tr>
        @endforelse

    </tbody>
</table>
</div>
@endsection