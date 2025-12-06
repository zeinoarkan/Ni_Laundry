@extends('layouts.main')
@section('title', 'Riwayat Pesanan')

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Riwayat Pesanan Saya</h5>
            <a href="/dashboard" class="btn btn-sm btn-primary">Pesanan Baru</a>
        </div>
        <div class="card-body">
            @if($pesanan->isEmpty())
                <div class="text-center py-5">
                    <p class="text-muted">Belum ada riwayat pesanan.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Layanan</th>
                                <th>Berat (Kg)</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pesanan as $p)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($p->tanggal_pesan)->format('d M Y H:i') }}</td>
                                <td>{{ $p->layanan->nama_layanan }}</td>
                                <td>{{ $p->berat }} Kg</td>
                                <td>Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                                <td>
                                    @if($p->status_pesanan == 'Pending')
                                        <span class="badge bg-secondary">Menunggu Konfirmasi</span>
                                    @elseif($p->status_pesanan == 'Diproses')
                                        <span class="badge bg-primary">Sedang Dicuci</span>
                                    @elseif($p->status_pesanan == 'Selesai')
                                        <span class="badge bg-success">Selesai</span>
                                    @else
                                        <span class="badge bg-dark">{{ $p->status_pesanan }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection