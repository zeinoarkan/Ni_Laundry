@extends('layouts.main')
@section('title', 'Tambah Admin')
@section('content')

<div class="card shadow" style="max-width: 500px; margin: auto;">
    <div class="card-header bg-primary text-white">Tambah Admin Baru</div>
    <div class="card-body">
        <form action="/admin/users" method="POST">
            @csrf
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required placeholder="Masukkan username unik">
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required placeholder="Minimal 6 karakter">
            </div>
            <div class="d-flex justify-content-between">
                <a href="/admin/users" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-success">Simpan Admin</button>
            </div>
        </form>
    </div>
</div>
@endsection