@extends('layouts.main')
@section('title', 'Riwayat Pesanan')

@section('content')

{{-- SAFETY BLOCK: Logika Variabel Otomatis --}}
@php
    $list_pesanan = $riwayat ?? $pesanan ?? $data ?? [];
@endphp

<div class="max-w-6xl mx-auto my-8 space-y-10">

    <div class="relative overflow-hidden bg-white/60 backdrop-blur-xl border border-white/60 rounded-[2.5rem] p-8 md:p-12 shadow-glass flex flex-col md:flex-row items-center justify-between gap-6 group"
         data-aos="fade-down" data-aos-duration="1000">
        
        <div class="absolute top-0 right-0 w-64 h-64 bg-brand-100/50 rounded-full blur-[80px] -mr-16 -mt-16 pointer-events-none animate-[pulse_6s_ease-in-out_infinite]"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-fresh-100/50 rounded-full blur-[60px] -ml-10 -mb-10 pointer-events-none animate-[pulse_5s_ease-in-out_infinite_reverse]"></div>
        
        <div class="relative z-10 text-center md:text-left">
            <h1 class="text-3xl md:text-4xl font-bold text-slate-800 mb-2 tracking-tight">Riwayat Transaksi</h1>
            <p class="text-slate-500 font-medium text-lg">Pantau status laundry Anda secara realtime.</p>
        </div>

        <div class="relative z-10" data-aos="zoom-in" data-aos-delay="200">
            <div class="inline-flex items-center gap-3 px-5 py-3 rounded-2xl bg-white border border-slate-100 shadow-sm transition-transform hover:scale-105 duration-300 cursor-default">
                <div class="w-10 h-10 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center text-xl animate-[bounce_3s_infinite]">
                    <i class="ph-fill ph-receipt"></i>
                </div>
                <div class="text-left">
                    <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Total Order</span>
                    <span class="block text-xl font-bold text-slate-800">{{ count($list_pesanan) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        @forelse($list_pesanan as $r)
        <div class="group relative bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm hover:shadow-[0_20px_40px_-15px_rgba(59,130,246,0.15)] hover:border-brand-200 hover:-translate-y-2 transition-all duration-500 ease-out flex flex-col h-full"
             data-aos="fade-up" 
             data-aos-delay="{{ ($loop->index % 3) * 100 }}">
            
            <div class="flex justify-between items-start mb-6 pb-4 border-b border-slate-50">
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Order ID</span>
                    <span class="font-mono text-sm font-bold text-slate-700 bg-slate-100 px-2 py-1 rounded-md group-hover:bg-brand-50 group-hover:text-brand-600 transition-colors">
                        #{{ str_pad($r->id_pesanan, 4, '0', STR_PAD_LEFT) }}
                    </span>
                </div>
                <div class="text-right">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Tanggal Masuk</span>
                    <span class="text-xs font-semibold text-slate-600 flex items-center gap-1 justify-end">
                        <i class="ph-bold ph-calendar-blank text-brand-500"></i> 
                        {{ date('d M Y', strtotime($r->tgl_masuk)) }}
                    </span>
                </div>
            </div>

            <div class="flex items-start gap-4 mb-6">
                <div class="w-12 h-12 rounded-2xl shrink-0 flex items-center justify-center text-2xl bg-brand-50 text-brand-600 group-hover:scale-110 group-hover:rotate-6 transition-transform duration-500">
                    <i class="ph-duotone ph-t-shirt"></i>
                </div>
                
                <div>
                    <h3 class="font-bold text-slate-800 text-lg leading-tight mb-1 group-hover:text-brand-600 transition-colors">
                        {{ $r->layanan->nama_layanan ?? $r->nama_layanan ?? '-' }}
                    </h3>
                    
                    <div class="flex items-center gap-2 text-xs font-medium text-slate-500">
                        <span class="bg-slate-50 px-2 py-0.5 rounded border border-slate-100">
                            {{ $r->berat }} Kg
                        </span>
                        
                        @if(isset($r->diskon) && $r->diskon > 0)
                            <span class="text-green-600 flex items-center gap-1 bg-green-50 px-2 py-0.5 rounded border border-green-100">
                                <i class="ph-fill ph-tag"></i> {{ $r->diskon }}% OFF
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-auto space-y-3">
                
                <div class="flex justify-between items-center text-sm group-hover:translate-x-1 transition-transform duration-300">
                    <span class="text-slate-400 font-medium text-xs uppercase">Status</span>
                    
                    @if($r->status_laundry == 'Baru')
                        <span class="px-3 py-1 rounded-full text-xs font-bold border bg-slate-100 text-slate-600 border-slate-200">
                            <i class="ph-bold ph-hourglass"></i> Menunggu
                        </span>
                    @elseif($r->status_laundry == 'Proses')
                        <span class="px-3 py-1 rounded-full text-xs font-bold border bg-brand-50 text-brand-600 border-brand-100 flex items-center gap-1">
                            <span class="relative flex h-2 w-2 mr-1">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-500"></span>
                            </span>
                            Diproses
                        </span>
                    @elseif($r->status_laundry == 'Selesai')
                        <span class="px-3 py-1 rounded-full text-xs font-bold border bg-green-50 text-green-600 border-green-100">
                            <i class="ph-bold ph-check-circle"></i> Selesai
                        </span>
                    @elseif($r->status_laundry == 'Diambil')
                        <span class="px-3 py-1 rounded-full text-xs font-bold border bg-slate-800 text-white border-slate-800">
                            <i class="ph-bold ph-package"></i> Diambil
                        </span>
                    @endif
                </div>

                <div class="flex justify-between items-center text-sm group-hover:translate-x-1 transition-transform duration-300 delay-75">
                    <span class="text-slate-400 font-medium text-xs uppercase">Bayar</span>
                    
                    @if($r->status_pembayaran == 'Lunas')
                        <span class="text-green-600 font-bold flex items-center gap-1">
                            <i class="ph-fill ph-check-circle"></i> Lunas
                        </span>
                    @else
                        <span class="text-orange-500 font-bold flex items-center gap-1">
                            <i class="ph-fill ph-warning-circle animate-pulse"></i> Belum Bayar
                        </span>
                    @endif
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-dashed border-slate-200 flex justify-between items-end">
                <span class="text-xs font-bold text-slate-400">Total Tagihan</span>
                <span class="text-xl font-bold text-slate-800">Rp {{ number_format($r->total_biaya, 0, ',', '.') }}</span>
            </div>

        </div>
        
        @empty
        <div class="col-span-full py-20 text-center flex flex-col items-center justify-center bg-white/50 border border-dashed border-slate-200 rounded-[2.5rem]" 
             data-aos="zoom-in">
            <div class="w-24 h-24 rounded-full bg-slate-50 flex items-center justify-center mb-6 shadow-inner animate-[bounce_3s_infinite]">
                <i class="ph-duotone ph-basket text-4xl text-slate-300"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2">Belum Ada Pesanan</h3>
            <p class="text-slate-500 max-w-xs mx-auto mb-8">Riwayat pesanan Anda akan muncul di sini.</p>
            <a href="/layanan" class="px-8 py-3 rounded-xl bg-brand-600 text-white font-bold hover:bg-brand-700 hover:scale-105 hover:shadow-glow transition-all shadow-lg shadow-brand-200">
                Buat Pesanan Baru
            </a>
        </div>
        @endforelse

    </div>
</div>

@endsection