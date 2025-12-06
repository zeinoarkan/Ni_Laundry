@extends('layouts.main')
@section('title', 'Kelola Pesanan')
@section('content')

<h3>Data Semua Pesanan</h3>
<div class="card shadow mt-3">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pelanggan</th>
                        <th>Layanan</th>
                        <th>Berat</th>
                        <th>Total (Rp)</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pesanan as $p)
                    <tr>
                        <td>#{{ $p->id_pesanan }}</td>
                        <td>{{ $p->pelanggan->nama }}</td>
                        <td>{{ $p->layanan->nama_layanan }}</td>
                        <td>{{ $p->berat }} Kg</td>
                        <td>{{ number_format($p->total_harga, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge bg-{{ $p->status_pesanan == 'Selesai' ? 'success' : ($p->status_pesanan == 'Pending' ? 'secondary' : 'warning') }}">
                                {{ $p->status_pesanan }}
                            </span>
                        </td>
                        <td>
                            <a href="/admin/pesanan/{{ $p->id_pesanan }}/edit" class="btn btn-sm btn-info text-white">Detail & Edit</a>
                            <form action="/admin/pesanan/{{ $p->id_pesanan }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pesanan ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">X</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection