@extends('layoutsPU.app')

@section('content')

<h3>Laporan Penjualan</h3>

<form method="GET" class="mb-3">
    <div class="row">
        <input type="hidden" name="from" value="laporan_penjualan">
        <div class="col-md-3">
            <input type="month" name="bulan" class="form-control"
                placeholder="Tiap bulan"
                value="{{ request('bulan') }}">
        </div>
        <div class="col-md-3">
            <select name="produk_id" class="form-control">
                <option value=""> Semua Produk </option>
                @foreach($produks as $produk)
                    <option value="{{ $produk->id }}"
                        {{ request('produk_id') == $produk->id ? 'selected' : '' }}>
                        {{ $produk->nama_produk }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="metode_pembayaran" class="form-control">
                <option value=""> Semua Metode </option>
                <option value="Tunai" {{ request('metode_pembayaran') == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                <option value="Transfer" {{ request('metode_pembayaran') == 'Transfer' ? 'selected' : '' }}>Transfer</option>
            </select>
        </div>
        <div class="col-md-5">
            <button class="btn btn-primary">Filter</button>
            <a href="{{ route('laporan.penjualan') }}" class="btn btn-secondary">Reset</a>
        </div>

    </div>
</form>
<div class="table-responsive">  
<table class="table table-bordered">
    <thead>
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
        </tr>
    </thead>

    <tbody>
        @php
            $totalTunai = 0;
            $totalTransfer = 0;
        @endphp

        @forelse($penjualans as $item)

        @php
            if(strtolower($item->metode_pembayaran) == 'tunai'){
                $totalTunai += $item->total_harga;
            } elseif(strtolower($item->metode_pembayaran) == 'transfer'){
                $totalTransfer += $item->total_harga;
            }
        @endphp

        <tr>
            <td>{{ $item->tanggal }}</td>
            <td>{{ $item->pembeli->nama_pembeli ?? 'Umum' }}</td>

            <td>
                @foreach($item->details as $d)
                    {{ $d->produk->nama_produk ?? '-' }} <br>
                @endforeach
            </td>

            <td>
                @foreach($item->details as $d)
                    {{ $d->jumlah ?? '-' }} <br>
                @endforeach
            </td>

            <td>
                @foreach($item->details as $d)
                    Rp {{ number_format($d->harga ?? 0,0,',','.') }} <br>
                @endforeach
            </td>

            <td>Rp {{ number_format($item->total_harga,0,',','.') }}</td>
            <td>Rp {{ number_format($item->pembayaran ?? 0,0,',','.') }}</td>
            <td>Rp {{ number_format($item->kembalian ?? 0,0,',','.') }}</td>
            <td>{{ $item->metode_pembayaran }}</td>
        </tr>

        @empty
        <tr>
            <td colspan="9" class="text-center">Tidak ada data</td>
        </tr>
        @endforelse

    </tbody>
</table>
</div>
{{-- <div class="mt-3">
    <h5>Total Penjualan:</h5>

    <p>Tunai : <strong>Rp {{ number_format($totalTunai,0,',','.') }}</strong></p>
    <p>Transfer : <strong>Rp {{ number_format($totalTransfer,0,',','.') }}</strong></p>
    <p>Total Keseluruhan :
        <strong>Rp {{ number_format($totalTunai + $totalTransfer,0,',','.') }}</strong>
    </p>
</div> --}}

<a href="{{ url('/laporan/penjualan/pdf') }}?{{ http_build_query(request()->all()) }}"class="btn btn-danger">PDF</a>
<a href="{{ url('/laporan/penjualan/excel') }}?{{ http_build_query(request()->all()) }}" class="btn btn-success">Excel</a>
@endsection