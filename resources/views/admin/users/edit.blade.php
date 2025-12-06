@extends('layouts.main')
@section('title', 'Edit Admin')
@section('content')

<div class="card shadow" style="max-width: 500px; margin: auto;">
    <div class="card-header bg-warning text-dark">Edit Data Admin</div>
    <div class="card-body">
        <form action="/admin/users/{{ $admin->id_admin }}" method="POST">
            @csrf @method('PUT')
            
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="{{ $admin->username }}" required>
            </div>
            
            <div class="mb-3">
                <label>Password Baru <small class="text-muted">(Kosongkan jika tidak ingin mengganti)</small></label>
                <input type="password" name="password" class="form-control" placeholder="********">
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="/admin/users" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Update Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection