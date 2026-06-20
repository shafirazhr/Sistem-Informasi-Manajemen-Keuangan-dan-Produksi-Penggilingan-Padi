@extends('layoutsadmin.app')

@section('content')

<h3>Tambah Penjualan</h3>

<form action="{{ route('penjualan.store') }}" method="POST">
@csrf

<div class="mb-3">
    <label>Tanggal</label>
    <input type="date" name="tanggal" class="form-control" required>
</div>

<div class="mb-3">
    <label>Pembeli</label>
    <select name="pembeli_id" id="pembeli" class="form-control" onchange="hitung()">
        <option value="">Umum</option>
        @foreach($pembelis as $p)
        <option value="{{ $p->id }}">{{ $p->nama_pembeli }}</option>
        @endforeach
    </select>
</div>

<hr>

<div id="produk-wrapper"></div>

<button type="button" class="btn btn-secondary mb-3" onclick="tambahProduk()">
+ Tambah Produk
</button>

<div class="mb-3">
    <label>Total</label>
    <input type="text" id="total" class="form-control" readonly>
</div>

<div class="mb-3">
    <label>Pembayaran</label>
    <input type="number" name="pembayaran" id="pembayaran" class="form-control" required>
</div>

<div class="mb-3">
    <label>Kembalian</label>
    <input type="text" id="kembalian" class="form-control" readonly>
</div>

<div class="mb-3">
    <label>Metode Pembayaran</label>
    <select name="metode_pembayaran" class="form-control" required>
        <option value="tunai">Tunai</option>
        <option value="transfer">Transfer</option>
    </select>
</div>

<button class="btn btn-secondary" onclick="window.history.back()">Batal</button>
<button class="btn btn-success" id="btnSimpan" disabled>Simpan</button>

</form>

<script>

function validasiForm(){

    let total = parseFloat(
        document.getElementById('total').value.replace(/\./g,'')
    ) || 0;

    let bayar = parseFloat(document.getElementById('pembayaran').value) || 0;
    let metode = document.querySelector('[name="metode_pembayaran"]').value;

    let validStok = true;

    // 🔥 CEK STOK
    document.querySelectorAll('.produk-item').forEach(row => {

        let select = row.querySelector('select');
        let jumlah = parseFloat(row.querySelector('input').value) || 0;

        let stokText = select.options[select.selectedIndex].text;
        let stok = parseFloat(stokText.match(/stok: (\d+)/)[1]);

        if(jumlah > stok){
            validStok = false;
        }
    });

    let validPembayaran = false;

    if(metode == 'tunai'){
        validPembayaran = bayar >= total;
    } else if(metode == 'transfer'){
        validPembayaran = bayar === total;
    }

    let validTotal = total > 0;

    // 🔥 AKTIFKAN BUTTON
    let btn = document.getElementById('btnSimpan');

    if(validStok && validPembayaran && validTotal){
        btn.disabled = false;
    } else {
        btn.disabled = true;
    }
}

document.addEventListener('input', function(){
    hitung();
    validasiForm();
});

document.addEventListener('change', validasiForm);

function tambahProduk(){
    let html = `
    <div class="row mb-2 produk-item">

        <div class="col-md-5">
            <select name="produk[]" class="form-control" onchange="hitung()">
                @foreach($produks as $p)
                <option value="{{ $p->id }}" 
                        data-harga1="{{ $p->harga_jual1 }}" 
                        data-harga2="{{ $p->harga_jual2 }}">
                    {{ $p->nama_produk }} (stok: {{ $p->stock }})
                </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <input type="number" name="jumlah[]" class="form-control" placeholder="Jumlah" oninput="hitung()">
        </div>

        <div class="col-md-3">
            <button type="button" 
                onclick="hapusProduk(this)" 
                class="btn btn-danger w-100">Hapus</button>
        </div>

    </div>
    `;

    document.getElementById('produk-wrapper').insertAdjacentHTML('beforeend', html);
}

function hapusProduk(btn){
    btn.closest('.produk-item').remove();
    hitung();
}

function hitung(){
    let total = 0;

    document.querySelectorAll('.produk-item').forEach(row => {

        let select = row.querySelector('select');
        let jumlah = parseFloat(row.querySelector('input').value) || 0;

        let harga1 = parseFloat(select.selectedOptions[0].dataset.harga1);
        let harga2 = parseFloat(select.selectedOptions[0].dataset.harga2);

        let pembeli = document.getElementById('pembeli').value;

        let harga = pembeli ? harga1 : harga2;

        total += harga * jumlah;
    });

    document.getElementById('total').value = total.toLocaleString('id-ID');

    hitungKembalian();
}

function hitungKembalian(){
    let total = parseFloat(
        document.getElementById('total').value.replace(/\./g,'')
    ) || 0;

    let bayar = parseFloat(document.getElementById('pembayaran').value) || 0;

    let kembalian = bayar - total;

    document.getElementById('kembalian').value = kembalian >= 0 
        ? kembalian.toLocaleString('id-ID') 
        : 0;
}

document.addEventListener('input', hitung);
document.getElementById('pembayaran').addEventListener('input', hitungKembalian);

</script>

@endsection