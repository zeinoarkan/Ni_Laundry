@extends('layouts.main')
@section('title', 'Beranda')

@section('content')
<style>
    .hero-banner {
        background: linear-gradient(135deg, #0d6efd 0%, #0099ff 100%), 
                    url('https://images.unsplash.com/photo-1545173168-9f1947eebb8f?q=80&w=1600&auto=format&fit=crop');
        background-size: cover;
        background-position: center;
        color: white;
        padding: 120px 20px;
        border-radius: 0 0 20px 20px;
        margin-top: -25px;
        text-align: center;
    }
    .hero-title { font-size: 3.5rem; font-weight: 800; text-shadow: 2px 2px 4px rgba(0,0,0,0.5); }
    .feature-icon { font-size: 3rem; color: #0d6efd; margin-bottom: 20px; }
</style>

<div class="hero-banner">
    <div class="container">
        <h1 class="hero-title mb-3">Ni Laundry</h1>
        <p class="lead mb-5 fs-4">
            Solusi pakaian bersih, wangi, dan rapi tanpa ribet.<br>
            Kami jemput kotor, kami antar bersih.
        </p>
        <a href="/layanan" class="btn btn-warning btn-lg fw-bold shadow px-5 py-3 rounded-pill">
            <i class="bi bi-basket-fill"></i> Pesan Sekarang
        </a>
    </div>
</div>

<div class="container my-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary">Kenapa Memilih Kami?</h2>
        <p class="text-muted">Kualitas terbaik untuk pakaian kesayangan Anda</p>
    </div>

    <div class="row text-center">
        <div class="col-md-4 mb-4">
            <div class="p-4 bg-white shadow-sm rounded-4 h-100 border hover-shadow">
                <i class="bi bi-truck feature-icon"></i>
                <h4 class="fw-bold">Antar Jemput Gratis</h4>
                <p class="text-muted">Hemat waktu Anda. Kurir kami siap menjemput dan mengantar cucian langsung ke depan pintu.</p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="p-4 bg-white shadow-sm rounded-4 h-100 border hover-shadow">
                <i class="bi bi-stopwatch feature-icon"></i>
                <h4 class="fw-bold">Proses Cepat</h4>
                <p class="text-muted">Layanan One Day Service tersedia. Pakaian bersih dan wangi dalam waktu singkat.</p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="p-4 bg-white shadow-sm rounded-4 h-100 border hover-shadow">
                <i class="bi bi-shield-check feature-icon"></i>
                <h4 class="fw-bold">Garansi Bersih</h4>
                <p class="text-muted">Kami menggunakan deterjen premium dan setrika uap untuk hasil maksimal.</p>
            </div>
        </div>
    </div>
    
    <div class="bg-primary text-white p-5 rounded-4 mt-5 text-center shadow">
        <h3>Tunggu apa lagi?</h3>
        <p class="mb-4">Dapatkan diskon khusus untuk member baru dan gratis cuci setelah 8x transaksi!</p>
        <a href="/layanan" class="btn btn-light btn-lg text-primary fw-bold">Lihat Daftar Harga & Pesan</a>
    </div>
</div>
@endsection