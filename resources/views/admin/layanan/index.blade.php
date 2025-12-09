@extends('layouts.main')
@section('title', 'Kelola Layanan')
@section('content')

<div class="d-flex justify-content-between mb-3">
    <h3>Daftar Layanan</h3>
    <a href="/admin/layanan/create" class="btn btn-primary">+ Tambah Layanan</a>
</div>

<div class="card shadow">
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Layanan</th>
                    <th>Jenis</th>
                    <th>Harga / Kg</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($layanan as $l)
                <tr>
                    <td>{{ $l->nama_layanan }}</td>
                    <td>{{ $l->jenis }}</td>
                    <td>Rp {{ number_format($l->harga, 0, ',', '.') }}</td>
                    <td>
                        <a href="/admin/layanan/{{ $l->id_layanan }}/edit" class="btn btn-sm btn-warning">Edit</a>
                        <form action="/admin/layanan/{{ $l->id_layanan }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection