@extends('layoutsadmin.app')

@section('content')

<h3>Edit Distributor</h3>

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('pembeli.update',$pembeli->id) }}" method="POST">
@csrf
@method('PUT')

<div class="mb-3">
    <label>Nama Pembeli</label>
    <input type="text" name="nama_pembeli" class="form-control"
           value="{{ $pembeli->nama_pembeli }}" required>
</div>

<div class="mb-3">
    <label>Alamat</label>
    <input type="text" name="alamat" class="form-control"
           value="{{ $pembeli->alamat }}">
</div>

<div class="mb-3">
    <label>No HP</label>
    <input type="text" name="no_hp" class="form-control"
           value="{{ $pembeli->no_hp }}" required>
</div>

<button class="btn btn-success">Update</button>
<a href="{{ route('pembeli.index') }}" class="btn btn-secondary">Kembali</a>

</form>

@endsection