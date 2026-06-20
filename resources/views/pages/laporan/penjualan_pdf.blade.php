<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>

    <style>
        body {
            font-family: Arial;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }

        th {
            background: #eee;
            padding: 6px;
            text-align: center;
        }

        td {
            padding: 5px;
        }

        .text-right {
            text-align: right;
        }

        .total {
            margin-top: 15px;
        }
    </style>
</head>

<body>

<h2>LAPORAN PENJUALAN</h2>

<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Pembeli</th>
            <th>Produk</th>
            <th>Berat</th>
            <th>Harga</th>
            <th>Total</th>
        </tr>
    </thead>

    <tbody>

        @php
            $totalTunai = 0;
            $totalTransfer = 0;
        @endphp

        @foreach($penjualans as $item)

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

            <td class="text-right">
                Rp {{ number_format($item->total_harga,0,',','.') }}
            </td>
        </tr>

        @endforeach

    </tbody>
</table>

<div class="total">
    <p><strong>Total Tunai:</strong> Rp {{ number_format($totalTunai,0,',','.') }}</p>
    <p><strong>Total Transfer:</strong> Rp {{ number_format($totalTransfer,0,',','.') }}</p>
    <p><strong>Total Keseluruhan:</strong>
        Rp {{ number_format($totalTunai + $totalTransfer,0,',','.') }}
    </p>
</div>

</body>
</html>