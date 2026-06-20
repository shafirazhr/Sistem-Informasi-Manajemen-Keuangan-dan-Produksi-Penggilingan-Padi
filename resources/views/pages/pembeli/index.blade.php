@extends('layoutsadmin.app')

@section('content')

<h3>Data Distributor</h3>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="card mb-3">
    <div class="card-header">
        Tambah Distributor
    </div>
    <div class="card-body">

        <form action="{{ route('pembeli.store') }}" method="POST">
            @csrf

            <div class="mb-2">
                <label>Nama Pembeli</label>
                <input type="text" name="nama_pembeli" class="form-control" required>
            </div>

            <div class="mb-2">
                <label>Alamat</label>
                <input type="text" name="alamat" class="form-control">
            </div>

            <div class="mb-2">
                <label>No HP</label>
                <input type="text" name="no_hp" class="form-control"
                    placeholder="+628xxxxxxxxxx"
                    required>
            </div>

            <button class="btn btn-success">Simpan</button>
            <a href="{{ route('pembeli.index') }}" class="btn btn-secondary">Reset</a>

        </form>

    </div>
</div>
<div class="table-responsive">  
<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Alamat</th>
            <th>No HP</th>
            <th>Keterangan</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>

        @forelse($pembelis as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->nama_pembeli }}</td>
            <td>{{ $item->alamat ?? '-' }}</td>
            <td>{{ $item->no_hp ?? '-' }}</td>

            <td>
                <span class="badge bg-success">
                    Harga Khusus (Harga 1)
                </span>
            </td>

            <td>
                <a href="{{ route('pembeli.edit',$item->id) }}" 
                   class="btn btn-warning btn-sm">
                    Edit
                </a>

                {{-- <form action="{{ route('pembeli.destroy',$item->id) }}" 
                      method="POST" 
                      style="display:inline">
                    @csrf
                    @method('DELETE')

                    <button class="btn btn-danger btn-sm"
                        onclick="return confirm('Yakin ingin menghapus?')">
                        Hapus
                    </button>
                </form> --}}
            </td>
        </tr>

        @empty
        <tr>
            <td colspan="6" class="text-center">Belum ada distributor</td>
        </tr>
        @endforelse

    </tbody>
</table>
</div>
<script>
document.addEventListener('DOMContentLoaded', function(){
    const input = document.querySelector('[name="no_hp"]');

    input.addEventListener('input', function(){
        if(!this.value.startsWith('+628')){
            this.value = '+628';
        }
    });
});
</script>

@endsection