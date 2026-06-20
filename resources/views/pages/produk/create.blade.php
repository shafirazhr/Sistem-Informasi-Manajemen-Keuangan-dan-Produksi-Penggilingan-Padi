@extends('layoutsPU.app')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Tambah Produk</h1>
    <a href="/produk" class="btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-body">

                <form action="/produk" method="POST">
                    @csrf

                    <div class="form-group mb-3">
                        <label>Nama Produk</label>
                        <input type="text" 
                               name="nama_produk" 
                               class="form-control @error('nama_produk') is-invalid @enderror"
                               value="{{ old('nama_produk') }}"
                               required>

                        @error('nama_produk')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label>Stock</label>
                        <input type="number" 
                               name="stock" 
                               class="form-control @error('stock') is-invalid @enderror"
                               value="{{ old('stock') }}"
                               required>

                        @error('stock')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label>Harga Jual 1</label>
                        <input type="number" 
                               step="0.01"
                               name="harga_jual1" 
                               class="form-control @error('harga_jual1') is-invalid @enderror"
                               value="{{ old('harga_jual1') }}"
                               required>

                        @error('harga_jual1')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Harga Jual 2 -->
                    <div class="form-group mb-3">
                        <label>Harga Jual 2</label>
                        <input type="number" 
                               step="0.01"
                               name="harga_jual2" 
                               class="form-control @error('harga_jual2') is-invalid @enderror"
                               value="{{ old('harga_jual2') }}"
                               required>

                        @error('harga_jual2')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Tombol -->
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            Simpan
                        </button>
                        <a href="/produk" class="btn btn-secondary">
                            Batal
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

@endsection