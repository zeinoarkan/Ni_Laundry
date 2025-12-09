@extends('layouts.main')
@section('title', 'Tambah Layanan')
@section('content')
<div class="card shadow" style="max-width: 600px">
    <div class="card-header">Tambah Layanan Baru</div>
    <div class="card-body">
        <form action="/admin/layanan" method="POST">
            @csrf
            <div class="mb-3">
                <label>Nama Layanan</label>
                <input type="text" name="nama_layanan" class="form-control" required placeholder="Contoh: Cuci Kering">
            </div>
            <div class="mb-3">
                <label>Jenis</label>
                <select name="jenis" class="form-control">
                    <option value="Kiloan">Kiloan/1KG</option>
                    <option value="Satuan">Satuan</option>
                    <option value="pakaianberat">Pakaian Berat</option>
                    <option value="Khusus">Khusus</option>
                    <option value="Tambahan">Tambahan</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Harga (Rp)</label>
                <input type="number" name="harga" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="/admin/layanan" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection