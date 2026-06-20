@extends('layoutsadmin.app')

@section('content')

<h3>Edit Penjualan</h3>

<form action="{{ route('penjualan.update',$penjualan->id) }}" method="POST">
@csrf
@method('PUT')

<div class="mb-3">
    <label>Tanggal</label>
    <input type="date" name="tanggal" 
        value="{{ $penjualan->tanggal }}" class="form-control" required>
</div>

<div class="mb-3">
    <label>Pembeli</label>
    <select name="pembeli_id" id="pembeli" class="form-control" onchange="hitung()">
        <option value="">Umum</option>
        @foreach($pembelis as $p)
        <option value="{{ $p->id }}" 
            {{ $penjualan->pembeli_id == $p->id ? 'selected' : '' }}>
            {{ $p->nama_pembeli }}
        </option>
        @endforeach
    </select>
</div>

<hr>

<h5>Detail Produk</h5>

<div id="produk-wrapper">

@foreach($penjualan->details as $d)
<div class="row mb-2 produk-item">

    <div class="col-md-4">
        <select name="produk[]" class="form-control" onchange="hitung()">
            @foreach($produks as $p)
            <option value="{{ $p->id }}"
                data-harga1="{{ $p->harga_jual1 }}"
                data-harga2="{{ $p->harga_jual2 }}"
                data-stok="{{ $p->stock }}"
                {{ $d->produk_id == $p->id ? 'selected' : '' }}>
                {{ $p->nama_produk }} (stok: {{ $p->stock }})
            </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3">
        <input type="number" name="jumlah[]" 
            value="{{ $d->jumlah }}" 
            class="form-control" oninput="hitung()" min="1">
    </div>

    <div class="col-md-3">
        <input type="number" name="harga[]" 
            value="{{ $d->harga }}" 
            class="form-control" readonly>
    </div>

    <div class="col-md-2">
        <button type="button" class="btn btn-danger btn-sm remove-item">X</button>
    </div>

</div>
@endforeach

</div>

<button type="button" class="btn btn-primary btn-sm mb-3" onclick="tambahProduk()">
+ Tambah Produk
</button>

<hr>

<div class="mb-3">
    <label>Total</label>
    <input type="text" id="total" class="form-control" readonly>
</div>

<div class="mb-3">
    <label>Pembayaran</label>
    <input type="number" name="pembayaran" id="pembayaran"
        value="{{ $penjualan->pembayaran }}" class="form-control" required>
</div>

<div class="mb-3">
    <label>Kembalian</label>
    <input type="text" id="kembalian" class="form-control" readonly>
</div>

<div class="mb-3">
    <label>Metode Pembayaran</label>
    <select name="metode_pembayaran" id="metode" class="form-control" required>
        <option value="tunai" {{ $penjualan->metode_pembayaran == 'tunai' ? 'selected' : '' }}>Tunai</option>
        <option value="transfer" {{ $penjualan->metode_pembayaran == 'transfer' ? 'selected' : '' }}>Transfer</option>
    </select>
</div>

<button type="button" class="btn btn-secondary" onclick="window.history.back()">Batal</button>
<button type="submit" id="btnSimpan" class="btn btn-success" disabled>Update</button>

</form>

<script>

function tambahProduk(){
    let html = `
    <div class="row mb-2 produk-item">
        <div class="col-md-4">
            <select name="produk[]" class="form-control" onchange="hitung()">
                @foreach($produks as $p)
                <option value="{{ $p->id }}"
                    data-harga1="{{ $p->harga_jual1 }}"
                    data-harga2="{{ $p->harga_jual2 }}"
                    data-stok="{{ $p->stock }}">
                    {{ $p->nama_produk }} (stok: {{ $p->stock }})
                </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <input type="number" name="jumlah[]" class="form-control" oninput="hitung()" min="1">
        </div>

        <div class="col-md-3">
            <input type="number" name="harga[]" class="form-control" readonly>
        </div>

        <div class="col-md-2">
            <button type="button" class="btn btn-danger btn-sm remove-item">X</button>
        </div>
    </div>
    `;
    document.getElementById('produk-wrapper').insertAdjacentHTML('beforeend', html);
}

function hitung(){
    let total = 0;
    let pembeli = document.getElementById('pembeli').value;

    document.querySelectorAll('.produk-item').forEach(row=>{
        let select = row.querySelector('select');
        let jumlah = parseFloat(row.querySelector('input').value) || 0;

        let harga1 = parseFloat(select.selectedOptions[0].dataset.harga1);
        let harga2 = parseFloat(select.selectedOptions[0].dataset.harga2);

        let harga = pembeli ? harga1 : harga2;

        row.querySelector('[name="harga[]"]').value = harga;

        total += harga * jumlah;
    });

    document.getElementById('total').value = total.toLocaleString('id-ID');

    hitungKembalian();
    validasiForm();
}

function hitungKembalian(){
    let total = parseFloat(document.getElementById('total').value.replace(/\./g,'')) || 0;
    let bayar = parseFloat(document.getElementById('pembayaran').value) || 0;

    let kembalian = bayar - total;

    document.getElementById('kembalian').value = kembalian >= 0 
        ? kembalian.toLocaleString('id-ID') : 0;
}

function validasiForm(){
    let total = parseFloat(document.getElementById('total').value.replace(/\./g,'')) || 0;
    let bayar = parseFloat(document.getElementById('pembayaran').value) || 0;
    let metode = document.getElementById('metode').value;

    let validStok = true;

    document.querySelectorAll('.produk-item').forEach(row=>{
        let select = row.querySelector('select');
        let jumlah = parseFloat(row.querySelector('input').value) || 0;
        let stok = parseFloat(select.selectedOptions[0].dataset.stok);

        if(jumlah > stok){
            validStok = false;
        }
    });

    let validPembayaran = false;

    if(metode == 'tunai'){
        validPembayaran = bayar >= total;
    } else {
        validPembayaran = bayar === total;
    }

    let btn = document.getElementById('btnSimpan');

    btn.disabled = !(validStok && validPembayaran && total > 0);
}

document.addEventListener('input', hitung);
document.addEventListener('change', hitung);

document.addEventListener('click', function(e){
    if(e.target.classList.contains('remove-item')){
        e.target.closest('.produk-item').remove();
        hitung();
    }
});

// 🔥 hitung awal
window.onload = hitung;

</script>

@endsection