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
                            <form action="/admin/pesanan/{{ $p->id_pesanan }}/update-status" method="POST">
                                @csrf
                                <select name="status_pesanan" class="form-select form-select-sm" 
                                        onchange="this.form.submit()" 
                                        style="width: 130px; border-color: {{ $p->status_pesanan == 'Selesai' ? '#198754' : ($p->status_pesanan == 'Diproses' ? '#0d6efd' : '#adb5bd') }}">
                                    
                                    <option value="Pending" {{ $p->status_pesanan == 'Pending' ? 'selected' : '' }}>
                                        Pending
                                    </option>
                                    <option value="Diproses" {{ $p->status_pesanan == 'Diproses' ? 'selected' : '' }}>
                                        Diproses
                                    </option>
                                    <option value="Selesai" {{ $p->status_pesanan == 'Selesai' ? 'selected' : '' }}>
                                        Selesai
                                    </option>
                                
                                </select>
                            </form>
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