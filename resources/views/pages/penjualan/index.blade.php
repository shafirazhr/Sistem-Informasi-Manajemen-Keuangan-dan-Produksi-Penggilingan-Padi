@extends('layoutsadmin.app')

@section('content')

<h3>Data Penjualan</h3>


<a href="{{ route('penjualan.create') }}" class="btn btn-primary mb-3">Tambah</a>
<form method="GET" class="row mb-3 align-items-end">

    <div class="col-md-3">
        <input type="date" name="dari" class="form-control" value="{{ request('dari') }}">
    </div>

    <div class="col-md-1 text-center">s/d</div>

    <div class="col-md-3">
        <input type="date" name="sampai" class="form-control" value="{{ request('sampai') }}">
    </div>

    <div class="col-md-2">
        <select name="sort" class="form-control">
            <option value="terbaru">Terbaru</option>
            <option value="lama">Terlama</option>
        </select>
    </div>

    <div class="col-md-3 d-flex gap-2">
        <button class="btn btn-primary flex-fill">Filter</button>
        <a href="{{ route('penjualan.index') }}" class="btn btn-secondary flex-fill">Reset</a>
    </div>
    
<div class="table-responsive">      
    <table class="table">
    <tr>
        <th>Tanggal</th>
        <th>Pembeli</th>
        <th>Produk</th>
        <th>Berat</th>
        <th>Harga</th>
        <th>Total</th>
        <th>Bayar</th>
        <th>Kembalian</th>
        <th>Pembayaran</th>
        <th>Aksi</th>
    </tr>

    @foreach($penjualans as $item)

            @php
                $produk = [];
                $berat = [];
                $harga = [];

                foreach($item->details as $d){
                    $produk[] = $d->produk->nama_produk;
                    $berat[] = $d->jumlah;
                    $harga[] = $d->harga;
                }
            @endphp

            <tr>
                <td>{{ $item->tanggal }}</td>
                <td>{{ $item->pembeli->nama_pembeli ?? 'Umum' }}</td>

                <td>{{ implode(', ', $produk) }}</td>
                <td>{{ implode(', ', $berat) }}</td>
                <td>{{ implode(', ', $harga) }}</td>

                <td>Rp {{ number_format($item->total_harga,0,',','.') }}</td>
                <td>Rp {{ number_format($item->pembayaran,0,',','.') }}</td>
                <td>Rp {{ number_format($item->kembalian,0,',','.') }}</td>
                <td>{{ ucfirst($item->metode_pembayaran) }}</td>
    <td><a href="{{ route('penjualan.edit',$item->id) }}" class="btn btn-warning btn-sm"> Edit </a> 
        <a href="{{ route('penjualan.nota',$item->id) }}" class="btn btn-info btn-sm"> Nota </a> 
        <form action="{{ route('penjualan.destroy',$item->id) }}" method="POST" style="display:inline"> @csrf @method('DELETE')
            <button class="btn btn-danger btn-sm"
                            onclick="return confirm('Yakin ingin menghapus?')">
                            Hapus
                        </button></td>
    </tr>
    @endforeach

    </table>
</div>

@endsection