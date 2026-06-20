<!DOCTYPE html>
<html>
<head>
<style>
body { font-family: Arial; font-size: 12px; }
table { width:100%; border-collapse: collapse; }
table, th, td { border:1px solid black; }
th { background:#eee; }
</style>
</head>
<body>

<h3 style="text-align:center;">Laporan Pengeluaran</h3>

<table>
<tr>
    <th>Tanggal</th>
    <th>Kategori</th>
    <th>Keterangan</th>
    <th>Total</th>
</tr>

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

<br>

<p><strong>Total Pengeluaran:</strong>
Rp {{ number_format($total,0,',','.') }}</p>

</body>
</html>