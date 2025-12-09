@extends('layouts.main')
@section('title', 'Edit Pesanan')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Edit Pesanan #{{ $pesanan->id_pesanan }}</h5>
            </div>
            <div class="card-body">
                <form action="/admin/pesanan/{{ $pesanan->id_pesanan }}" method="POST">
                    @csrf 
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Pelanggan</label>
                        <input type="text" class="form-control bg-light" value="{{ $pesanan->pelanggan->nama }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Layanan Dipilih</label>
                        <input type="text" class="form-control bg-light" value="{{ $pesanan->layanan->nama_layanan }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Status Pesanan</label>
                        <select name="status_pesanan" class="form-select">
                            <option value="Pending" {{ $pesanan->status_pesanan == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Diproses" {{ $pesanan->status_pesanan == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="Selesai" {{ $pesanan->status_pesanan == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Berat Cucian (Kg)</label>
                            <input type="number" step="0.01" name="berat" class="form-control" value="{{ $pesanan->berat }}" required>
                            <small class="text-muted">Isi sesuai timbangan riil.</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Total Tagihan (Rp)</label>
                            <input type="number" name="total_harga" class="form-control" value="{{ $pesanan->total_harga }}" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Uang Muka / Sudah Bayar (Rp)</label>
                        <input type="number" name="jumlah_bayar" class="form-control" value="{{ $pesanan->jumlah_bayar ?? 0 }}">
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="/admin/pesanan" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection