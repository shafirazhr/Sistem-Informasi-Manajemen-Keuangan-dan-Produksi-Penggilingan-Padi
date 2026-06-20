@extends('layoutsAdmin.app') 

@section('content')

<div class="container-fluid">

    <h4 class="mb-3">Dashboard</h4>

    <style>
        .chart-box {
            height: 180px;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        }

        h6 {
            font-size: 14px;
        }

        h4 {
            font-size: 20px;
        }
    </style>

    <!-- ROW 1 -->
    <div class="row">

        <!-- KEUANGAN -->
        <div class="col-md-6 mb-3">
            <div class="card p-2">
                <h6 class="mb-2">Keuangan</h6>
                <div class="chart-box">
                    <canvas id="keuanganChart"></canvas>
                </div>
            </div>
        </div>

        <!-- PENJUALAN -->
        <div class="col-md-6 mb-3">
            <div class="card p-2">
                <h6 class="mb-2">Penjualan</h6>
                <div class="chart-box">
                    <canvas id="penjualanChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- ROW 2 -->
    <div class="row">

        <!-- LABA -->
        {{-- <div class="col-md-4 mb-3">
            <div class="card text-center p-3">
                <h6>Laba / Rugi</h6>
                <h4 class="{{ $laba < 0 ? 'text-danger' : 'text-success' }}">
                    Rp {{ number_format($laba,0,',','.') }}
                </h4>
            </div>
        </div> --}}

        <!-- STOK -->
        <div class="col-md-8 mb-3">
            <div class="card p-2">
                <h6 class="mb-2">Stok Produk</h6>
                <div class="chart-box">
                    <canvas id="stokChart"></canvas>
                </div>
            </div>
        </div>

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
new Chart(document.getElementById('keuanganChart'), {
    type: 'line',
    data: {
        labels: {!! json_encode($labels) !!},
        datasets: [
            {
                label: 'Pemasukan',
                data: {!! json_encode($dataPemasukan) !!},
                borderColor: 'green',
                backgroundColor: 'rgba(0,128,0,0.2)',
                tension: 0.4,
                fill: true
            },
            {
                label: 'Pengeluaran',
                data: {!! json_encode($dataPengeluaran) !!},
                borderColor: 'red',
                backgroundColor: 'rgba(255,0,0,0.2)',
                tension: 0.4,
                fill: true
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// PENJUALAN
new Chart(document.getElementById('penjualanChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($tanggal) !!},
        datasets: [{
            label: 'Penjualan',
            data: {!! json_encode($totalPenjualan) !!},
            backgroundColor: 'blue'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});


// STOK PIE
new Chart(document.getElementById('stokChart'), {
    type: 'pie',
    data: {
        labels: ['Padi','Beras','Bekatul','Sekam'],
        datasets: [{
            data: [{{ $padi }}, {{ $beras }}, {{ $bekatul }}, {{ $sekam }}],
            backgroundColor: ['gray','green','orange','brown']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

</script>

<h4 class="mb-3">Laporan Penjualan Harian</h4>

<form method="GET" class="mb-3">
    <div class="row">
        <div class="col-md-3">
            <input type="date" name="dari_harian" class="form-control"
                value="{{ request('dari') }}">
        </div>

        <div class="col-md-3">
            <input type="date" name="sampai_harian" class="form-control"
                value="{{ request('sampai') }}">
        </div>

        <div class="col-md-3">
            <button class="btn btn-primary">Filter</button>
            <a href="{{ url('dashboardAdmin') }}" class="btn btn-secondary">Reset</a>
        </div>
    </div>
</form>

<div class="table-responsive">  
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Total Penjualan</th>
        </tr>
    </thead>

    <tbody>
        @forelse($laporanHarian as $d)
        <tr>
            <td>{{ $d->tanggal }}</td>
            <td>Rp {{ number_format($d->total,0,',','.') }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="2" class="text-center">Tidak ada data</td>
        </tr>
        @endforelse
    </tbody>
</table>
</div>
<form method="GET" class="mb-3">
    <div class="row">
        <div class="col-md-3">
            <input type="month" name="dari_bulanan" class="form-control"
                value="{{ request('dari') }}">
        </div>

        <div class="col-md-3">
            <input type="month" name="sampai_bulanan" class="form-control"
                value="{{ request('sampai') }}">
        </div>

        <div class="col-md-3">
            <button class="btn btn-primary">Filter</button>
            <a href="{{ url('dashboardAdmin') }}" class="btn btn-secondary">Reset</a>
        </div>
    </div>
</form>

<div class="table-responsive">  
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Bulan</th>
            <th>Total Penjualan</th>
        </tr>
    </thead>

    <tbody>
        @forelse($laporanBulanan as $d)
        <tr>
            <td>{{ $d->bulan }}</td>
            <td>Rp {{ number_format($d->total,0,',','.') }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="2" class="text-center">Tidak ada data</td>
        </tr>
        @endforelse
    </tbody>
</table>
</div>
@endsection