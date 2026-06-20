<!DOCTYPE html>
<html>
<head>
    <title>Nota</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            padding: 6px;
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body onload="window.print()">

<div style="display:flex; align-items:center; gap:15px;">
    
    <img src="{{ asset('img/logo.jpg') }}" width="80">

    <div>
        <h2 style="margin:0;">Nota Penjualan</h2>
        <h3 style="margin:0;">UD Sumber Pangan</h3>
        <p style="margin:0;">Desa Sugihwaras, Kalitengah Lamongan</p>
        <p style="margin:0;">08123456789</p>
    </div>
</div>

<hr>

<p>Tanggal: {{ $penjualan->tanggal }}</p>
<p>Pembeli: {{ $penjualan->pembeli->nama_pembeli ?? 'Umum' }}</p>
<p>Metode Pembayaran: {{ ucfirst($penjualan->metode_pembayaran) }}</p>
    </tr>

<hr>

<table border="1">
    <tr>
        <th>Produk</th>
        <th>Berat</th>
        <th>Harga</th>
        <th>Subtotal</th>
    </tr>

    @foreach($penjualan->details as $d)
    <tr>
        <td>{{ $d->produk->nama_produk }}</td>
        <td>{{ $d->jumlah }}</td>
        <td class="text-right">Rp {{ number_format($d->harga,0,',','.') }}</td>
        <td class="text-right">Rp {{ number_format($d->jumlah * $d->harga,0,',','.') }}</td>
    </tr>
    @endforeach
</table>

<hr>

<table width="100%">
    <tr>
        <td class="text-right"><strong>Total</strong></td>
        <td class="text-right">Rp {{ number_format($penjualan->total_harga,0,',','.') }}</td>
    </tr>
    <tr>
        <td class="text-right"><strong>Bayar</strong></td>
        <td class="text-right">Rp {{ number_format($penjualan->pembayaran,0,',','.') }}</td>
    </tr>
<hr
    <tr>
        <td class="text-right"><strong>Kembalian</strong></td>
        <td class="text-right">Rp {{ number_format($penjualan->kembalian,0,',','.') }}</td>
    </tr>
</table>

<hr>

</body>
</html>