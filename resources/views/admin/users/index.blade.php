@extends('layouts.main')
@section('title', 'Kelola Admin')
@section('content')

<div class="d-flex justify-content-between mb-3">
    <h3>Daftar Pengguna Admin</h3>
    <a href="/admin/users/create" class="btn btn-primary">+ Tambah Admin Baru</a>
</div>

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card shadow">
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($admins as $a)
                <tr>
                    <td>{{ $a->id_admin }}</td>
                    <td>
                        {{ $a->username }}
                        @if(Auth::guard('admin')->id() == $a->id_admin)
                            <span class="badge bg-success ms-2">Saya</span>
                        @endif
                    </td>
                    <td>
                        <a href="/admin/users/{{ $a->id_admin }}/edit" class="btn btn-sm btn-warning">Edit</a>
                        
                        @if(Auth::guard('admin')->id() != $a->id_admin)
                            <form action="/admin/users/{{ $a->id_admin }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus admin ini? Akses akan hilang permanen.')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection