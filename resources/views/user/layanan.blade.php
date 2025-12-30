@extends('layouts.main')
@section('title', 'Katalog dan Pesan Layanan')

@section('content')

<div class="flex flex-col lg:flex-row gap-8 items-start my-8"> 
    
    <div class="w-full lg:w-3/5 space-y-8">
        
        <div class="relative overflow-hidden bg-brand-600 rounded-[2.5rem] p-8 md:p-10 text-white shadow-glow group" 
             data-aos="fade-right" data-aos-duration="800">
             
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-[60px] -mr-16 -mt-16 group-hover:bg-white/20 transition-colors duration-700"></div>
            <div class="absolute bottom-0 left-0 w-40 h-40 bg-brand-900/30 rounded-full blur-[40px] -ml-10 -mb-10"></div>
            
            <div class="relative z-10">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 backdrop-blur-md border border-white/20 text-xs font-bold uppercase tracking-wider mb-4">
                   Premium Service
                </div>
                <h1 class="text-3xl md:text-4xl font-bold mb-3 leading-tight">Pilih Paket Terbaik</h1>
                <p class="text-brand-100 font-medium text-lg max-w-md">Kami merawat pakaian Anda dengan standar kebersihan tertinggi.</p>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-5">
            @foreach($layanan as $l)
            
            {{-- LOGIKA DETEKSI ICON BERDASARKAN NAMA LAYANAN --}}
            @php
                $name = strtolower($l->nama_layanan);

                // Deteksi Keyword
                if(str_contains($name, 'setrika')) {
                    $icon = 'mdi mdi-iron-outline';
                    $color = 'bg-orange-50 text-orange-600';
                } elseif(str_contains($name, 'karpet')) {
                    $icon = 'ph-duotone ph-rug';
                    $color = 'bg-red-50 text-red-600';
                } elseif(str_contains($name, 'sepatu') || str_contains($name, 'sneaker')) {
                    $icon = 'ph-duotone ph-sneaker';
                    $color = 'bg-yellow-50 text-yellow-600';
                } elseif(str_contains($name, 'sprei') || str_contains($name, 'selimut') || str_contains($name, 'bantal')) {
                    $icon = 'ph-duotone ph-bed';
                    $color = 'bg-purple-50 text-purple-600';
                } elseif(str_contains($name, 'bed') || str_contains($name, 'selimut') || str_contains($name, 'bantal')) {
                    $icon = 'ph-duotone ph-bed';
                    $color = 'bg-purple-50 text-purple-600';
                } elseif(str_contains($name, 'boneka')) {
                    $icon = 'ph-duotone ph-finn-the-human';
                    $color = 'bg-pink-50 text-pink-600';
                } elseif(str_contains($name, 'jas') || str_contains($name, 'dry')) {
                    $icon = 'ph-duotone ph-coat-hanger';
                    $color = 'bg-slate-50 text-slate-600';
                } elseif(str_contains($name, 'jaket') || str_contains($name, 'dry')) {
                    $icon = 'ph-duotone ph-coat-hanger';
                    $color = 'bg-slate-50 text-slate-600';
                } elseif(str_contains($name, 'almamater') || str_contains($name, 'dry')) {
                    $icon = 'ph-duotone ph-coat-hanger';
                    $color = 'bg-slate-50 text-slate-600';
                } elseif(str_contains($name, 'tas')) {
                    $icon = 'ph-duotone ph-handbag';
                    $color = 'bg-amber-50 text-amber-600';
                } elseif(str_contains($name, 'reguler')) {
                    $icon = 'ph-duotone ph-scales';
                    $color = 'bg-blue-50 text-blue-600';
                } elseif(str_contains($name, 'kilat')) {
                    $icon = 'ph-duotone ph-scales';
                    $color = 'bg-blue-50 text-blue-600';
                } elseif(str_contains($name, 'express')) {
                    $icon = 'ph-duotone ph-scales';
                    $color = 'bg-blue-50 text-blue-600';
                } elseif(str_contains($name, 'kemeja') || str_contains($name, 'pcs')) {
                    $icon = 'ph-duotone ph-t-shirt';
                    $color = 'bg-emerald-50 text-emerald-600';
                } elseif(str_contains($name, 'cuci') || str_contains($name, 'tambahan')) {
                    $icon = 'ph-duotone ph-washing-machine';
                    $color = 'bg-emerald-50 text-emerald-600';
                }
            @endphp

            <div class="group relative bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-[0_20px_40px_-15px_rgba(59,130,246,0.15)] hover:border-brand-200 hover:-translate-y-2 transition-all duration-500 ease-out h-full flex flex-col justify-between"
                 data-aos="fade-up" 
                 data-aos-delay="{{ ($loop->index % 4) * 100 }}">
                
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 rounded-2xl {{ $color }} flex items-center justify-center text-2xl group-hover:scale-110 group-hover:rotate-6 transition-transform duration-500 cubic-bezier(0.34, 1.56, 0.64, 1)">
                        <i class="{{ $icon }}"></i>
                    </div>
                    
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $l->jenis == 'Kiloan' ? 'bg-blue-50 text-blue-600 border-blue-100' : 'bg-orange-50 text-orange-600 border-orange-100' }}">
                        {{ $l->jenis }}
                    </span>
                </div>
                
                <div>
                    <h3 class="text-lg font-bold text-slate-800 mb-2 group-hover:text-brand-600 transition-colors duration-300">{{ $l->nama_layanan }}</h3>
                    <ul class="space-y-1 mb-6 opacity-80 group-hover:opacity-100 transition-opacity">
                    </ul>
                </div>
                
                <div class="mt-auto pt-4 border-t border-slate-50 flex items-center justify-between">
                    <div>
                        <span class="text-xs text-slate-400 font-bold uppercase tracking-wider block mb-0.5">Harga</span>
                        <div class="flex items-baseline gap-1">
                            <span class="text-2xl font-bold text-slate-800 group-hover:text-brand-600 transition-colors duration-300">Rp {{ number_format($l->harga, 0, ',', '.') }}</span>
                            <span class="text-xs text-slate-400 font-medium">/ {{ $l->jenis == 'Kiloan' ? 'Kg' : 'Pcs' }}</span>
                        </div>
                    </div>
                    
                    <button type="button" 
                            onclick="selectServiceWithAnim(this, {{ $l->id_layanan }})" 
                            class="select-btn w-10 h-10 rounded-full bg-slate-50 text-slate-400 hover:bg-brand-600 hover:text-white flex items-center justify-center transition-all shadow-sm group-hover:shadow-md active:scale-90"
                            title="Pilih Layanan Ini">
                        <i class="ph-bold ph-plus transition-transform duration-300"></i>
                    </button>
                </div>
            </div>
            @endforeach
        </div>

        <div class="p-6 rounded-[2rem] bg-brand-50/50 border border-brand-100 flex gap-4 items-start text-sm text-slate-600"
             data-aos="fade-up" data-aos-delay="200">
            <div class="shrink-0 w-8 h-8 rounded-full bg-brand-100 text-brand-600 flex items-center justify-center">
                <i class="ph-fill ph-info text-lg"></i>
            </div>
            <ul class="space-y-1.5 list-disc list-inside mt-1 marker:text-brand-400">
                <li>Waktu pengerjaan standar <span class="font-bold text-brand-700">24-48 jam</span>.</li>
                <li>Berat akan ditimbang ulang secara akurat oleh admin/kurir saat penjemputan.</li>
                <li>Pembayaran dilakukan di akhir (setelah nota keluar).</li>
            </ul>
        </div>
    </div>

    <div class="w-full lg:w-2/5 sticky top-28 z-20" data-aos="fade-left" data-aos-duration="800">
        
        <div class="bg-white/80 backdrop-blur-xl rounded-[2.5rem] shadow-glass border border-white/60 p-8 relative overflow-hidden">
            
            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-50 rounded-bl-[100px] -z-10"></div>

            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 rounded-2xl bg-slate-900 text-white flex items-center justify-center text-xl font-bold shadow-lg shadow-slate-200">
                    <i class="ph-duotone ph-pencil-simple"></i>
                </div>
                <div>
                    <h2 class="font-bold text-xl text-slate-900">Form Pesanan</h2>
                    <p class="text-xs text-slate-500 font-medium">Isi data ringkas untuk request pickup.</p>
                </div>
            </div>

            <form action="/pesan" method="POST" class="space-y-6">
                @csrf
                
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Pilih Layanan</label>
                    <div class="relative group">
                        <select name="id_layanan" id="serviceSelect" required class="w-full appearance-none bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-2xl focus:bg-white focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 block p-4 outline-none transition-all font-semibold cursor-pointer hover:border-brand-300">
                            <option value="" disabled selected>Pilih Paket Laundry...</option>
                            @foreach($layanan as $l)
                                <option value="{{ $l->id_layanan }}">{{ $l->nama_layanan }} (Rp {{ number_format($l->harga, 0) }})</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 text-slate-400 pointer-events-none group-hover:text-brand-500 transition-colors">
                            <i class="ph-bold ph-caret-down text-lg"></i>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Estimasi Berat (Kg)</label>
                    <div class="relative group">
                        <input type="number" name="berat" min="1" placeholder="Contoh: 3" required 
                               class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-2xl focus:bg-white focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 block p-4 pl-12 outline-none transition-all placeholder:text-slate-400 font-semibold hover:border-brand-300">
                        <div class="absolute inset-y-0 left-0 flex items-center px-4 text-slate-400 group-focus-within:text-brand-500 transition-colors">
                            <i class="ph-bold ph-scales text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Metode Serah Terima</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer relative">
                            <input type="radio" name="metode" value="Antar Jemput" class="peer sr-only" checked>
                            <div class="p-4 rounded-2xl border-2 border-slate-100 bg-white hover:bg-slate-50 peer-checked:border-brand-500 peer-checked:bg-brand-50 peer-checked:text-brand-700 transition-all text-center h-full flex flex-col items-center justify-center gap-2 group shadow-sm hover:-translate-y-1 duration-300">
                                <i class="ph-duotone ph-moped text-3xl text-slate-400 group-hover:text-brand-500 peer-checked:text-brand-600 transition-colors"></i>
                                <span class="text-xs font-bold">Antar Jemput</span>
                                <div class="absolute top-2 right-2 w-2 h-2 rounded-full bg-brand-500 opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                            </div>
                        </label>
                        <label class="cursor-pointer relative">
                            <input type="radio" name="metode" value="Drop Off" class="peer sr-only">
                            <div class="p-4 rounded-2xl border-2 border-slate-100 bg-white hover:bg-slate-50 peer-checked:border-brand-500 peer-checked:bg-brand-50 peer-checked:text-brand-700 transition-all text-center h-full flex flex-col items-center justify-center gap-2 group shadow-sm hover:-translate-y-1 duration-300">
                                <i class="ph-duotone ph-storefront text-3xl text-slate-400 group-hover:text-brand-500 peer-checked:text-brand-600 transition-colors"></i>
                                <span class="text-xs font-bold">Drop Off</span>
                                <div class="absolute top-2 right-2 w-2 h-2 rounded-full bg-brand-500 opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                            </div>
                        </label>
                    </div>
                </div>

                <button type="submit" class="w-full py-4 px-6 rounded-2xl bg-slate-900 text-white font-bold text-sm shadow-xl shadow-slate-200 hover:shadow-2xl hover:shadow-brand-200 hover:-translate-y-1 hover:bg-brand-600 transition-all duration-300 flex items-center justify-center gap-2 mt-4 group">
                    <span>Kirim Pesanan</span>
                    <i class="ph-bold ph-paper-plane-right group-hover:translate-x-1 transition-transform"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function selectServiceWithAnim(btn, id) {
        // 1. Logika Select Form
        const select = document.getElementById('serviceSelect');
        if(select) {
            select.value = id;
            select.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            // Highlight efek pada select box
            select.classList.add('ring-4', 'ring-brand-500/30', 'border-brand-500');
            setTimeout(() => {
                select.classList.remove('ring-4', 'ring-brand-500/30', 'border-brand-500');
            }, 1000);
        }

        // 2. Animasi Tombol GSAP (Spin & Scale)
        if(typeof gsap !== 'undefined') {
            const icon = btn.querySelector('i');
            
            // Animasi tombol bounce
            gsap.to(btn, {
                scale: 0.8,
                duration: 0.1,
                yoyo: true,
                repeat: 1
            });

            // Animasi icon berputar
            gsap.to(icon, {
                rotation: 360,
                duration: 0.6,
                ease: "back.out(1.7)"
            });

            // Ubah warna tombol sesaat
            gsap.to(btn, {
                backgroundColor: "#2563eb", // brand-600
                color: "#ffffff",
                duration: 0.2,
                onComplete: () => {
                    // Kembalikan warna setelah 1 detik (opsional, atau biarkan tetap terpilih)
                    gsap.to(btn, {
                        backgroundColor: "#f8fafc", // slate-50
                        color: "#94a3b8", // slate-400
                        duration: 0.5,
                        delay: 0.5
                    });
                }
            });
        }
    }
</script>

@endsection