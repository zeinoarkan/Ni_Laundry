@extends('layouts.main')
@section('title', 'Riwayat Pesanan')

@section('content')

{{-- 1. SCRIPT MIDTRANS & CUSTOM STYLES --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('Mid-client-3uRO8uoHQJBxU2ZN') }}"></script>

{{-- Style Tambahan untuk Animasi Floating --}}
<style>
    @keyframes floatSlow {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        50% { transform: translate(20px, -20px) rotate(5deg); }
    }
    @keyframes floatMedium {
        0%, 100% { transform: translate(0, 0) scale(1); }
        50% { transform: translate(-15px, 15px) scale(1.05); }
    }
    .animate-float-slow { animation: floatSlow 8s ease-in-out infinite; }
    .animate-float-medium { animation: floatMedium 6s ease-in-out infinite; }
</style>

<div class="max-w-6xl mx-auto my-8 space-y-10">

    {{-- 2. HEADER SECTION (Fully Animated Background) --}}
    <div class="relative overflow-hidden bg-white/60 backdrop-blur-xl border border-white/60 rounded-[2.5rem] p-8 md:p-12 shadow-glass flex flex-col md:flex-row items-center justify-between gap-6 group isolate"
         data-aos="fade-down" data-aos-duration="1000">
        
        <div class="absolute top-0 right-0 w-64 h-64 bg-brand-100/60 rounded-full blur-[80px] -mr-16 -mt-16 pointer-events-none animate-float-slow -z-10"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-fresh-100/60 rounded-full blur-[60px] -ml-10 -mb-10 pointer-events-none animate-float-medium -z-10"></div>
        
        <div class="relative z-10 text-center md:text-left">
            <h1 class="text-3xl md:text-4xl font-bold text-slate-800 mb-2 tracking-tight" data-aos="fade-right" data-aos-delay="100">Riwayat Transaksi</h1>
            <p class="text-slate-500 font-medium text-lg" data-aos="fade-right" data-aos-delay="200">Pantau status laundry dan pembayaran Anda.</p>
        </div>

        <div class="relative z-10 flex flex-col md:flex-row gap-4 items-center">
            {{-- Statistik Total Order (Bouncing Icon) --}}
            <div class="inline-flex items-center gap-3 px-5 py-3 rounded-2xl bg-white border border-slate-100 shadow-sm transition-all hover:scale-105 hover:shadow-md duration-300 cursor-default" data-aos="zoom-in" data-aos-delay="300">
                <div class="w-10 h-10 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center text-xl animate-[bounce_3s_ease-in-out_infinite]">
                    <i class="ph-fill ph-receipt"></i>
                </div>
                <div class="text-left">
                    <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Total Order</span>
                    <span class="block text-xl font-bold text-slate-800">{{ $pesanan->count() }}</span>
                </div>
            </div>

            {{-- Tombol Pesan Lagi --}}
            <a href="/layanan" class="px-6 py-3 rounded-2xl bg-brand-600 text-white font-bold text-sm hover:bg-brand-700 transition-all flex items-center gap-2 shadow-lg shadow-brand-200 hover:-translate-y-1 active:scale-95" data-aos="zoom-in" data-aos-delay="400">
                <i class="ph-bold ph-plus"></i> Pesan Baru
            </a>
        </div>
    </div>

    {{-- 3. GRID LAYOUT PESANAN (Staggered Entrance) --}}
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        @forelse($pesanan as $p)
        <div class="group relative bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm hover:shadow-[0_25px_50px_-12px_rgba(59,130,246,0.25)] hover:border-brand-300 hover:-translate-y-2 transition-all duration-500 cubic-bezier(0.34, 1.56, 0.64, 1) flex flex-col h-full"
             data-aos="fade-up" 
             data-aos-delay="{{ ($loop->index % 3) * 150 }}">
            
            {{-- Bagian Atas: Order ID & Tanggal --}}
            <div class="flex justify-between items-start mb-6 pb-4 border-b border-slate-50 group-hover:border-slate-100 transition-colors">
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Order ID</span>
                    <span class="font-mono text-sm font-bold text-slate-700 bg-slate-100 px-2 py-1 rounded-md group-hover:bg-brand-600 group-hover:text-white transition-all duration-300">
                        #{{ str_pad($p->id_pesanan, '0', STR_PAD_LEFT) }}
                    </span>
                </div>
                <div class="text-right">
                     <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Tanggal</span>
                     <span class="text-xs font-bold text-slate-600 flex items-center gap-1 justify-end">
                        <i class="ph-bold ph-calendar-blank text-brand-500 opacity-0 group-hover:opacity-100 transition-opacity -translate-x-2 group-hover:translate-x-0 duration-300"></i>
                        {{ \Carbon\Carbon::parse($p->tanggal_pesan)->format('d M Y') }}
                     </span>
                </div>
            </div>

            {{-- Bagian Tengah: Info Layanan (Icon Rotate & Scale) --}}
            <div class="flex items-start gap-4 mb-6">
                <div class="w-12 h-12 rounded-2xl shrink-0 flex items-center justify-center text-2xl bg-brand-50 text-brand-600 group-hover:scale-110 group-hover:rotate-[15deg] group-hover:bg-brand-600 group-hover:text-white transition-all duration-500 cubic-bezier(0.34, 1.56, 0.64, 1) shadow-sm group-hover:shadow-brand-200/50">
                    <i class="ph-duotone ph-t-shirt"></i>
                </div>
                
                <div>
                    <h3 class="font-bold text-slate-800 text-lg leading-tight mb-2 group-hover:text-brand-700 transition-colors">
                        {{ $p->layanan->nama_layanan }}
                    </h3>
                    
                    <div class="flex flex-wrap items-center gap-2 text-xs font-medium text-slate-500">
                        <span class="bg-slate-50 px-2 py-0.5 rounded border border-slate-100 group-hover:border-slate-200 transition-colors">
                            {{ $p->berat }} Kg
                        </span>
                        <span class="bg-slate-50 px-2 py-0.5 rounded border border-slate-100 group-hover:border-slate-200 transition-colors">
                             {{ $p->metode }}
                        </span>
                        @if($p->berat > 8)
                            <span class="text-purple-600 bg-purple-50 px-2 py-0.5 rounded border border-purple-100 font-bold text-[10px] uppercase animate-pulse">
                                Promo >8Kg
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Bagian Status (Nudge on Hover) --}}
            <div class="mb-6 space-y-2">
                <div class="flex justify-between items-center text-sm group-hover:translate-x-1 transition-transform duration-300 ease-out">
                    <span class="text-slate-400 font-medium text-xs uppercase">Status Order</span>
                    
                    @if($p->status_pesanan == 'Pending')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-bold border border-amber-100 shadow-sm">
                            <span class="relative flex h-2 w-2 mr-0.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                            </span> Menunggu Bayar
                        </span>
                    @elseif($p->status_pesanan == 'Diproses')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-brand-50 text-brand-700 text-xs font-bold border border-brand-100 shadow-sm">
                             <i class="ph-bold ph-spinner animate-spin text-brand-500"></i> Diproses
                        </span>
                    @elseif($p->status_pesanan == 'Selesai')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-100 shadow-sm">
                            <i class="ph-bold ph-check-circle text-emerald-500"></i> Selesai
                        </span>
                    @endif
                </div>
            </div>

            {{-- Bagian Bawah: Harga & Aksi --}}
            <div class="mt-auto pt-4 border-t border-dashed border-slate-200 flex flex-col gap-4 group-hover:border-brand-200 transition-colors">
                <div class="flex justify-between items-end">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Tagihan</span>
                    <div class="text-right">
                        @if($p->total_harga == 0)
                             <span class="text-xl font-bold text-emerald-500 animate-pulse">GRATIS</span>
                        @else
                            @if($p->berat > 8 || ($p->berat * $p->layanan->harga > $p->total_harga))
                                <span class="block text-xs text-slate-400 font-medium line-through opacity-70">
                                    Rp {{ number_format($p->berat * $p->layanan->harga, 0, ',', '.') }}
                                </span>
                            @endif
                            <span class="text-xl font-bold text-slate-800 group-hover:text-brand-700 transition-colors">
                                Rp {{ number_format($p->total_harga, 0, ',', '.') }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- TOMBOL AKSI (Animated) --}}
                @if($p->status_pesanan == 'Pending' && $p->total_harga > 0)
                    @if($p->snap_token)
                        {{-- Tombol Bayar dengan Pulse Attention --}}
                        <button onclick="bayarSekarang('{{ $p->snap_token }}', '{{ $p->id_pesanan }}')" 
                                class="w-full py-3 rounded-xl bg-slate-900 text-white font-bold text-sm shadow-lg shadow-slate-200 hover:bg-brand-600 hover:shadow-brand-300 hover:-translate-y-1 active:scale-95 transition-all flex items-center justify-center gap-2 animate-[pulse_2s_ease-in-out_infinite] hover:animate-none">
                            <i class="ph-bold ph-credit-card"></i> Bayar Sekarang
                        </button>
                    @else
                        <div class="w-full py-2.5 text-center text-xs text-red-500 font-medium bg-red-50 rounded-lg border border-red-100 flex items-center justify-center gap-1">
                            <i class="ph-bold ph-warning-circle"></i> Error: Token Gagal
                        </div>
                    @endif
                @elseif($p->status_pesanan == 'Pending' && $p->total_harga == 0)
                    <div class="w-full py-2.5 text-center text-xs text-emerald-600 font-bold bg-emerald-50 rounded-lg border border-emerald-100 flex items-center justify-center gap-1">
                        <i class="ph-bold ph-clock"></i> Menunggu Konfirmasi Admin
                    </div>
                @elseif($p->status_pesanan == 'Selesai')
                     <a href="/layanan" class="w-full py-2.5 rounded-xl border-2 border-slate-100 text-slate-600 font-bold text-sm hover:bg-slate-50 hover:border-slate-200 hover:text-slate-900 active:scale-95 transition-all text-center">
                        Pesan Lagi
                    </a>
                @endif
            </div>

        </div>
        
        @empty
        {{-- EMPTY STATE (Bouncing Animation) --}}
        <div class="col-span-full py-24 text-center flex flex-col items-center justify-center bg-white/50 border-2 border-dashed border-slate-200 rounded-[2.5rem] group hover:border-brand-200 transition-colors" 
             data-aos="zoom-in">
            <div class="w-24 h-24 rounded-full bg-slate-50 flex items-center justify-center mb-6 shadow-inner animate-[bounce_3s_ease-in-out_infinite] group-hover:bg-brand-50 transition-colors">
                <i class="ph-duotone ph-basket text-4xl text-slate-300 group-hover:text-brand-400 transition-colors"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2">Belum Ada Pesanan</h3>
            <p class="text-slate-500 max-w-xs mx-auto mb-8">Riwayat pesanan Anda akan muncul di sini.</p>
            <a href="/layanan" class="px-8 py-3 rounded-xl bg-brand-600 text-white font-bold hover:bg-brand-700 hover:scale-105 hover:shadow-glow active:scale-95 transition-all shadow-lg shadow-brand-200 flex items-center gap-2">
                <i class="ph-bold ph-plus"></i> Buat Pesanan Baru
            </a>
        </div>
        @endforelse

    </div>
</div>

{{-- 4. LOGIKA JAVASCRIPT MIDTRANS --}}
<script type="text/javascript">
    function bayarSekarang(snapToken, orderId) {
        if(!snapToken) {
            alert("Token pembayaran tidak ditemukan!");
            return;
        }
        snap.pay(snapToken, {
            onSuccess: function(result){ window.location.href = '/pesanan/sukses/' + orderId; },
            onPending: function(result){ alert("Menunggu pembayaran Anda!"); location.reload(); },
            onError: function(result){ alert("Pembayaran gagal!"); location.reload(); },
            onClose: function(){ alert('Anda menutup popup tanpa menyelesaikan pembayaran'); }
        });
    }
</script>

@endsection