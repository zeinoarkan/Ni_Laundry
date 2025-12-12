@extends('layouts.main')
@section('title', 'Layanan & Order')

@section('content')

<div class="flex flex-col lg:flex-row gap-8 items-start"> 
    
    <div class="w-full lg:w-3/5 space-y-6">
        
        <div class="bg-white/95 md:bg-white/60 md:backdrop-blur-md p-8 rounded-[2rem] border border-white/60 shadow-sm" 
             data-aos="fade-right" data-aos-duration="600">
            <h1 class="text-3xl font-bold text-slate-800 mb-2">Katalog Layanan</h1>
            <p class="text-slate-500 font-medium">Pilih paket laundry sesuai kebutuhan pakaian Anda.</p>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            @foreach($layanan as $l)
            <div class="group relative bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition-transform duration-300 h-full will-change-transform"
                 data-aos="fade-up" 
                 data-aos-delay="{{ ($loop->index % 4) * 100 }}"
                 data-aos-anchor-placement="top-bottom">
                
                <div class="flex justify-between items-start mb-4">
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $l->jenis == 'Kiloan' ? 'bg-blue-50 text-blue-600' : 'bg-orange-50 text-orange-600' }}">
                        {{ $l->jenis }}
                    </span>
                </div>
                
                <h3 class="text-lg font-bold text-slate-800 mb-1">{{ $l->nama_layanan }}</h3>
                <p class="text-slate-400 text-sm mb-4 line-clamp-2">Treatment deep clean & steam finish.</p>
                
                <div class="flex items-baseline gap-1 mt-auto pt-4 border-t border-slate-50">
                    <span class="text-2xl font-bold text-brand-600">Rp {{ number_format($l->harga, 0, ',', '.') }}</span>
                    <span class="text-xs text-slate-400 font-medium">/ {{ $l->jenis == 'Kiloan' ? 'Kg' : 'Pcs' }}</span>
                </div>
            </div>
            @endforeach
        </div>

        <div class="p-6 rounded-[2rem] bg-brand-50/50 border border-brand-100 flex gap-4 items-start text-sm text-slate-600"
             data-aos="fade-up" data-aos-delay="200">
            <i class="ph-fill ph-info text-brand-500 text-xl mt-0.5"></i>
            <ul class="space-y-1 list-disc list-inside">
                <li>Waktu pengerjaan standar 24-48 jam.</li>
                <li>Berat akan ditimbang ulang oleh admin/kurir.</li>
                <li>Pembayaran dilakukan di akhir (setelah nota keluar).</li>
            </ul>
        </div>
    </div>

    <div class="w-full lg:w-2/5 sticky top-24 z-20" data-aos="fade-left" data-aos-duration="800">
        
        <div class="bg-white/95 md:bg-white/80 md:backdrop-blur-lg rounded-[2.5rem] shadow-lg border border-white/50 p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center font-bold shadow-md">
                    <i class="ph-bold ph-pencil-simple"></i>
                </div>
                <div>
                    <h2 class="font-bold text-xl text-slate-900">Form Pesanan</h2>
                    <p class="text-xs text-slate-500 font-medium">Isi data untuk request pickup.</p>
                </div>
            </div>

            <form action="/pesan" method="POST" class="space-y-5">
                @csrf
                
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Layanan</label>
                    <div class="relative">
                        <select name="id_layanan" required class="w-full appearance-none bg-slate-50 border border-slate-100 text-slate-900 text-sm rounded-2xl focus:bg-white focus:border-brand-500 block p-4 outline-none transition-all font-semibold cursor-pointer hover:bg-slate-100">
                            <option value="" disabled selected>Pilih Layanan...</option>
                            @foreach($layanan as $l)
                                <option value="{{ $l->id_layanan }}">{{ $l->nama_layanan }} (Rp {{ number_format($l->harga, 0) }})</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 text-slate-400 pointer-events-none">
                            <i class="ph-bold ph-caret-down"></i>
                        </div>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Estimasi Berat (Kg)</label>
                    <div class="relative group">
                        <input type="number" name="berat" min="1" placeholder="Contoh: 3" required 
                               class="w-full bg-slate-50 border border-slate-100 text-slate-900 text-sm rounded-2xl focus:bg-white focus:border-brand-500 block p-4 pl-12 outline-none transition-all placeholder:text-slate-400 font-semibold">
                        <div class="absolute inset-y-0 left-0 flex items-center px-4 text-slate-400 group-focus-within:text-brand-500 transition-colors">
                            <i class="ph-bold ph-scales text-lg"></i>
                        </div>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Metode</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="metode" value="Antar Jemput" class="peer sr-only" checked>
                            <div class="p-4 rounded-2xl border-2 border-slate-100 bg-white hover:bg-slate-50 peer-checked:border-brand-500 peer-checked:bg-brand-50 peer-checked:text-brand-600 transition-all text-center h-full flex flex-col items-center justify-center gap-1 group">
                                <i class="ph-duotone ph-moped text-2xl mb-1 group-hover:scale-110 transition-transform"></i>
                                <span class="text-xs font-bold">Antar Jemput</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="metode" value="Drop Off" class="peer sr-only">
                            <div class="p-4 rounded-2xl border-2 border-slate-100 bg-white hover:bg-slate-50 peer-checked:border-brand-500 peer-checked:bg-brand-50 peer-checked:text-brand-600 transition-all text-center h-full flex flex-col items-center justify-center gap-1 group">
                                <i class="ph-duotone ph-storefront text-2xl mb-1 group-hover:scale-110 transition-transform"></i>
                                <span class="text-xs font-bold">Drop Off</span>
                            </div>
                        </label>
                    </div>
                </div>

                <button type="submit" class="w-full py-4 px-6 rounded-2xl bg-slate-900 text-white font-bold text-sm shadow-lg hover:shadow-xl hover:-translate-y-0.5 hover:bg-brand-600 transition-all duration-300 flex items-center justify-center gap-2 mt-4">
                    <span>Kirim Pesanan</span>
                    <i class="ph-bold ph-paper-plane-right"></i>
                </button>
            </form>
        </div>
    </div>
</div>

@endsection