@extends('layouts.main')
@section('title', 'Admin Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Pesanan</h5>
                <h2>{{ $total_pesanan }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Pendapatan</h5>
                <h2>Rp {{ number_format($pendapatan, 0, ',', '.') }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">Pesanan Terbaru</div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Pelanggan</th>
                    <th>Layanan</th>
                    <th>Berat (Kg)</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pesanan_terbaru as $p)
                <tr>
                    <td>{{ $p->pelanggan->nama }}</td>
                    <td>{{ $p->layanan->nama_layanan }}</td>
                    <td>{{ $p->berat }}</td>
                    <td>
                        <span class="badge bg-{{ $p->status_pesanan == 'Selesai' ? 'success' : 'warning' }}">
                            {{ $p->status_pesanan }}
                        </span>
                    </td>
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
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection