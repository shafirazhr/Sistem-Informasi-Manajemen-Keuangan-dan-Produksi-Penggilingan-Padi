@extends('layoutsPU.app')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Laporan Stok Produk</h1>
    <a href="/produk/create" class="btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Produk
    </a>
</div>

<div class="row">
<div class="col">
<div class="card">
<div class="card-body">

<div class="table-responsive">  
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Stock</th>
                <th>Harga Beli Terakhir</th>
                <th>Harga Jual 1</th>
                <th>Harga Jual 2</th>
                <th>Aksi</th>
            </tr>
        </thead>

        @if (count($produks) < 1)
        <tbody>
            <tr>
                <td colspan="6" class="text-center pt-3">
                    Tidak ada data produk.
                </td>
            </tr>
        </tbody>
        @else
        <tbody>
            @foreach ($produks as $item)
            <tr>
                <td>{{ $item->nama_produk }}</td>
                <td>{{ $item->stock }}</td>

                <td>
                    @if($item->nama_produk == 'Padi')
                        Rp {{ number_format($hargaBeliPadi,0,',','.') }}
                    @else
                        -
                    @endif
                </td>

                <td>{{ number_format($item->harga_jual1,0,',','.') }}</td>
                <td>{{ number_format($item->harga_jual2,0,',','.') }}</td>

                <td>
                    <a href="/produk/{{ $item->id }}/edit" 
                    class="btn btn-sm btn-warning">
                        <i class="fas fa-pen"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
        @endif

    </table>
</div>
</div>
</div> 
</div> 
</div> 

@endsection