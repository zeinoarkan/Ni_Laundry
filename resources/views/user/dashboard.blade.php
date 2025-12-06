@extends('layouts.main')
@section('title', 'Beranda')

@section('content')
<style>
    /* Styling Khusus untuk Landing Page User */
    .hero-section {
        background: linear-gradient(135deg, #0d6efd 0%, #0099ff 100%);
        color: white;
        padding: 60px 20px;
        border-radius: 15px;
        margin-bottom: 40px;
        position: relative;
        overflow: hidden;
    }
    .hero-pattern {
        position: absolute;
        right: -50px;
        top: -50px;
        opacity: 0.1;
        font-size: 15rem;
    }
    .card-service {
        transition: transform 0.3s;
        border: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    .card-service:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px rgba(0,0,0,0.1);
    }
    .price-tag {
        font-size: 1.2rem;
        font-weight: bold;
        color: #0d6efd;
    }
</style>

<div class="hero-section shadow">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h1 class="display-5 fw-bold">Halo, {{ Auth::user()->nama }}!</h1>
            <p class="lead">Selamat datang di Ni Laundry. Cucian bersih, wangi, dan rapi siap kami antar.</p>
            
            <div class="d-flex gap-3 mt-4">
                <div class="bg-white text-primary px-4 py-2 rounded-3 shadow-sm">
                    <small class="text-muted d-block">Progres Bonus</small>
                    <span class="h4 fw-bold">{{ Auth::user()->progres_kg }}</span> / 8 Kg
                </div>
                
                @if(Auth::user()->bonus > 0)
                <div class="bg-warning text-dark px-4 py-2 rounded-3 shadow-sm d-flex align-items-center">
                    <span class="fs-4 me-2"></span>
                    <div>
                        <small class="d-block fw-bold">Bonus Tersedia!</small>
                        <span>Gratis Cuci Berikutnya</span>
                    </div>
                </div>
                @endif
            </div>
            
            <div class="mt-4">
                <a href="#form-order" class="btn btn-light btn-lg fw-bold text-primary shadow-sm">Buat Pesanan Baru</a>
                <a href="/riwayat" class="btn btn-outline-light btn-lg ms-2">Cek Riwayat</a>
            </div>
        </div>
        
        <div class="col-md-4 d-none d-md-block text-end">
            <i class="hero-pattern">🧺</i> </div>
    </div>
</div>

<div class="container">
    <div class="row">
        
        <div class="col-lg-7 mb-4">
            <h4 class="mb-3 text-primary fw-bold"> Layanan Kami</h4>
            <div class="row g-3">
                @foreach($layanan as $l)
                <div class="col-md-6">
                    <div class="card card-service h-100">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">{{ $l->nama_layanan }}</h5>
                            <span class="badge bg-info text-dark mb-2">{{ $l->jenis }}</span>
                            <p class="card-text text-muted small">Layanan cuci terbaik dengan sabun premium dan pewangi tahan lama.</p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="price-tag">Rp {{ number_format($l->harga, 0, ',', '.') }}</span>
                                <small class="text-muted">/ {{ $l->jenis == 'Kiloan' ? 'Kg' : 'Pcs' }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="alert alert-light border mt-4">
                <h6 class="fw-bold"><i class="bi bi-info-circle"></i> Informasi Penting:</h6>
                <ul class="mb-0 small text-muted">
                    <li>Layanan antar jemput tersedia pukul 06.30 - 21.00.</li>
                    <li>Berat cucian akan ditimbang ulang oleh kurir/admin kami untuk akurasi.</li>
                    <li>Pembayaran dapat dilakukan saat pakaian diterima kembali (COD).</li>
                </ul>
            </div>
        </div>

        <div class="col-lg-5" id="form-order">
            <div class="card shadow border-0 sticky-top" style="top: 20px; z-index: 1;">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 text-center fw-bold">Form Pesanan</h5>
                </div>
                <div class="card-body p-4">
                    <form action="/pesan" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Layanan</label>
                            <select name="id_layanan" class="form-select form-select-lg bg-light" required>
                                <option value="" selected disabled>-- Pilih Laundry --</option>
                                @foreach($layanan as $l)
                                    <option value="{{ $l->id_layanan }}">
                                        {{ $l->nama_layanan }} (Rp {{ number_format($l->harga, 0, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Perkiraan Berat (Kg)</label>
                            <div class="input-group">
                                <input type="number" name="berat" class="form-control form-control-lg bg-light" placeholder="0" min="1" required>
                                <span class="input-group-text">Kg</span>
                            </div>
                            <small class="text-muted">*Hanya estimasi, berat asli ditentukan admin.</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Metode Serah Terima</label>
                            <div class="d-grid gap-2">
                                <input type="radio" class="btn-check" name="metode" id="antar" value="Antar Jemput" checked>
                                <label class="btn btn-outline-primary" for="antar">Antar Jemput (Driver ke Lokasi)</label>

                                <input type="radio" class="btn-check" name="metode" id="drop" value="Drop Off">
                                <label class="btn btn-outline-secondary" for="drop">Drop Off (Antar Sendiri)</label>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg fw-bold shadow-sm">
                                Kirim Pesanan Sekarang 
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection