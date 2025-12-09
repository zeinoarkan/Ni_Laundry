@extends('layouts.main')
@section('title', 'Monitoring Diskon')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Monitoring Diskon & Poin Pelanggan</h3>
    <div class="alert alert-info py-2 px-3 mb-0 border-0 shadow-sm">
        <i class="bi bi-info-circle"></i> Target Bonus: <strong>8 Kg</strong> = Gratis Cuci
    </div>
</div>

<div class="card shadow border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nama Pelanggan</th>
                        <th>No HP</th>
                        <th style="width: 30%;">Progres (Kg)</th>
                        <th>Status Bonus</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pelanggan as $p)
                    <tr>
                        <td class="fw-bold">{{ $p->nama }}</td>
                        <td>{{ $p->no_hp }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="me-2 fw-bold text-muted small">{{ $p->progres_kg }} Kg</span>
                                <div class="progress flex-grow-1" style="height: 10px;">
                                    @php 
                                        $persen = ($p->progres_kg / 8) * 100;
                                        if($persen > 100) $persen = 100;
                                    @endphp
                                    <div class="progress-bar {{ $persen >= 100 ? 'bg-success' : 'bg-warning' }}" 
                                         role="progressbar" 
                                         style="width: {{ $persen }}%">
                                    </div>
                                </div>
                                <span class="ms-2 small text-muted">8Kg</span>
                            </div>
                        </td>
                        <td>
                            @if($p->bonus > 0)
                                <span class="badge bg-success animate-pulse">
                                    <i class="bi bi-ticket-perforated-fill"></i> Ada Bonus!
                                </span>
                            @else
                                <span class="badge bg-secondary text-opacity-50">Belum Ada</span>
                            @endif
                        </td>
                        <td>
                            @if($p->bonus > 0)
                                <form action="/admin/diskon/{{ $p->id_pelanggan }}/reset" method="POST" onsubmit="return confirm('Reset bonus pelanggan ini secara manual?');">
                                    @csrf 
                                    <button class="btn btn-sm btn-outline-danger" title="Pakai/Hapus Bonus Manual">
                                        <i class="bi bi-x-circle"></i> Reset
                                    </button>
                                </form>
                            @else
                                <small class="text-muted">-</small>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
</style>
@endsection