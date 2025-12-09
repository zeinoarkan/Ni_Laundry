@extends('layouts.main')
@section('title', 'Edit Layanan')
@section('content')
<div class="card shadow" style="max-width: 600px">
    <div class="card-header">Edit Layanan</div>
    <div class="card-body">
        <form action="/admin/layanan/{{ $layanan->id_layanan }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label>Nama Layanan</label>
                <input type="text" name="nama_layanan" class="form-control" value="{{ $layanan->nama_layanan }}" required>
            </div>
            <div class="mb-3">
                <label>Jenis</label>
                <select name="jenis" class="form-control">
                    <option value="Kiloan" {{ $layanan->jenis == 'Kiloan' ? 'selected' : '' }}>Kiloan</option>
                    <option value="Satuan" {{ $layanan->jenis == 'Satuan' ? 'selected' : '' }}>Satuan</option>
                    <option value="Pakaian Berat" {{ $layanan->jenis == 'Pakaian Berat' ? 'selected' : '' }}>Pakaian Berat</option>
                    <option value="Khusus" {{ $layanan->jenis == 'Khusus' ? 'selected' : '' }}>Khusus</option>
                    <option value="Tambahan" {{ $layanan->jenis == 'Tambahan' ? 'selected' : '' }}>Tambahan</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Harga (Rp)</label>
                <input type="number" name="harga" class="form-control" value="{{ $layanan->harga }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="/admin/layanan" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection