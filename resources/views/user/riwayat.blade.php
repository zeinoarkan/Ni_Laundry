@extends('layouts.main')
@section('title', 'Riwayat Pesanan')

@section('content')

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<div class="container mt-4">
    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class="bi bi-clock-history"></i> Riwayat Pesanan Saya</h5>
            <a href="/layanan" class="btn btn-sm btn-light text-primary fw-bold">+ Pesan Lagi</a>
        </div>
        <div class="card-body">
            @if($pesanan->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-basket fs-1"></i>
                    <p class="mt-2">Belum ada riwayat pesanan.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Layanan</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pesanan as $p)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($p->tanggal_pesan)->format('d M Y, H:i') }}</td>
                                <td>
                                    <strong>{{ $p->layanan->nama_layanan }}</strong><br>
                                    <small class="text-muted">{{ $p->berat }} Kg ({{ $p->metode }})</small>
                                </td>
                                <td>
                                    @if($p->total_harga == 0)
                                        <span class="badge bg-success">GRATIS (Bonus 1Kg)</span>
                                    
                                    @else
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</span>
                                            
                                            @if( ($p->berat * $p->layanan->harga) > $p->total_harga )
                                                <small class="text-success" style="font-size: 0.75rem;">
                                                    <i class="bi bi-tag-fill"></i> Hemat 1Kg (Bonus)
                                                </small>
                                                <small class="text-decoration-line-through text-muted" style="font-size: 0.75rem;">
                                                    Rp {{ number_format($p->berat * $p->layanan->harga, 0, ',', '.') }}
                                                </small>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($p->status_pesanan == 'Pending')
                                        <span class="badge bg-warning text-dark">⏳ Menunggu Pembayaran</span>
                                    @elseif($p->status_pesanan == 'Diproses')
                                        <span class="badge bg-primary">🫧 Sedang Dicuci</span>
                                    @elseif($p->status_pesanan == 'Selesai')
                                        <span class="badge bg-success">✅ Selesai</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $p->status_pesanan }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($p->status_pesanan == 'Pending' && $p->snap_token)
                                        <button onclick="bayar('{{ $p->id_pesanan }}', '{{ $p->snap_token }}')" class="btn btn-success btn-sm fw-bold shadow-sm">
                                            <i class="bi bi-credit-card-2-front"></i> Bayar Sekarang
                                        </button>
                                    @elseif($p->status_pesanan == 'Diproses')
                                        <small class="text-muted"><i class="bi bi-check-circle"></i> Lunas</small>
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

<script type="text/javascript">
    function bayar(id_pesanan, token) {
        snap.pay(token, {
            onSuccess: function(result){
                window.location.href = '/pesanan/sukses/' + id_pesanan;
            },
            onPending: function(result){
                alert("Menunggu pembayaran! Silakan selesaikan pembayaran Anda.");
                location.reload();
            },
            // JIKA ERROR
            onError: function(result){
                alert("Pembayaran gagal atau dibatalkan.");
                location.reload();
            }
        });
    }
</script>

@endsection