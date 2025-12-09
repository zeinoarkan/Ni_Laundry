@extends('layouts.main')
@section('title', 'Dashboard Pelanggan')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">Halo, {{ Auth::user()->nama }}!</h4>
                        <p class="mb-0">Selamat datang kembali di Ni Laundry.</p>
                    </div>
                    <div class="text-end">
                        <h2 class="mb-0">{{ Auth::user()->progres_kg }} / 8 Kg</h2>
                        <small>Progres Bonus Cuci Gratis</small>
                        @if(Auth::user()->bonus > 0)
                            <br><span class="badge bg-warning text-dark mt-1">🎉 Anda Punya Bonus Gratis Cuci!</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Buat Pesanan Baru</h5>
                </div>
                <div class="card-body">
                    <form action="/pesan" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Pilih Layanan</label>
                            <select name="id_layanan" class="form-select" required>
                                <option value="" selected disabled>-- Pilih Jenis Laundry --</option>
                                @foreach($layanan as $l)
                                    <option value="{{ $l->id_layanan }}">
                                        {{ $l->nama_layanan }} - Rp {{ number_format($l->harga, 0, ',', '.') }} /kg
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Estimasi Berat (Kg)</label>
                            <input type="number" name="berat" class="form-control" placeholder="Contoh: 3" min="1" required>
                            <div class="form-text">Berat pasti akan ditimbang ulang oleh admin saat penjemputan.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Metode Layanan</label>
                            <select name="metode" class="form-select">
                                <option value="Antar Jemput">Antar Jemput (Driver kami ke lokasi Anda)</option>
                                <option value="Drop Off">Drop Off (Antar sendiri ke outlet)</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">Kirim Pesanan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection