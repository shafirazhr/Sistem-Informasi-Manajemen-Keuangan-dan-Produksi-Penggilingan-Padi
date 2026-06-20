@extends('layoutsPU.app')

@section('content')

<h3>Transaksi Pembelian Padi</h3>

<div class="alert alert-info">
    Stok Padi Saat Ini: <strong>{{ $stokPadi }}</strong>
</div>

<a href="{{ route('pgabahs.create') }}" class="btn btn-primary mb-3">
    Tambah Transaksi
</a>

@if(session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
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
        <a href="{{ route('pgabahs.index') }}" class="btn btn-secondary w-50">Reset</a>
    </div>

</form>

</form>
<div class="table-responsive">  
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Pemasok</th>
            <th>Jumlah</th>
            <th>Harga</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($pgabahs as $item)
        <tr>
            <td>{{ $item->tanggal }}</td>
            <td>{{ $item->nama_pemasok }}</td>
            <td>{{ $item->jumlah_gabah }}</td>
            <td>Rp {{ number_format($item->harga,0,',','.') }}</td>
            <td>
                <a href="{{ route('pgabahs.edit',$item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('pgabahs.destroy', $item->id) }}" method="POST" 
                    onsubmit="return confirm('Yakin menghapus transaksi?')">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-danger btn-sm">
                        Hapus
                    </button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="5" class="text-center">Belum ada transaksi</td></tr>
        @endforelse
    </tbody>
</table>
</div>
@endsection