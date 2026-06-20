<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial; font-size: 11px; }
        table { width:100%; border-collapse: collapse; }
        table, th, td { border:1px solid black; }
        th { background:#eee; }
        th, td { padding:5px; text-align:center; }
    </style>
</head>
<body>

<h3 style="text-align:center;">Laporan Keseluruhan</h3>

<table>
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
        @foreach($data as $item)
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
        @endforeach
    </tbody>
</table>

<br><br>

<h4>Ringkasan</h4>

<p><strong>Total Pendapatan:</strong> Rp {{ number_format($total_penjualan,0,',','.') }}</p>
<p><strong>Total Pengeluaran:</strong> Rp {{ number_format($total_pengeluaran,0,',','.') }}</p>
<p><strong>Laba / Rugi:</strong> Rp {{ number_format($laba,0,',','.') }}</p>

</body>
</html>