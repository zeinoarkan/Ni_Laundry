@extends('layouts.main')
@section('title', 'Beranda')

@section('content')
<div class="space-y-16 md:space-y-24 my-8">

    <div class="grid lg:grid-cols-5 gap-8 items-stretch">
        
        <div class="lg:col-span-3 relative overflow-hidden rounded-[2.5rem] bg-brand-600 shadow-glow group min-h-[300px]" 
             data-aos="fade-right">
             
            <img src="https://images.unsplash.com/photo-1604335399105-a0c585fd81a1?q=80&w=2070&auto=format&fit=crop" 
                 class="absolute inset-0 w-full h-full object-cover opacity-30 mix-blend-overlay transition-transform duration-1000 group-hover:scale-110">
            <div class="absolute inset-0 bg-gradient-to-r from-brand-900/90 to-brand-600/40"></div>
            
            <div class="relative z-10 p-8 md:p-12 h-full flex flex-col justify-center items-start text-white">
                <span class="inline-block px-3 py-1 rounded-full bg-white/20 backdrop-blur-md border border-white/10 text-xs font-bold tracking-widest uppercase mb-6 animate-pulse">
                    Welcome Back
                </span>
                <h1 class="text-3xl md:text-5xl font-bold leading-tight mb-6">
                    {{-- LOGIKA BARU: Handle Nama Null untuk Tamu --}}
                    Halo, {{ Auth::user()->nama ?? 'Pelanggan' }}! 
                </h1>
                <p class="text-brand-100 text-lg max-w-md mb-8 leading-relaxed">
                    Jangan biarkan cucian menumpuk. Serahkan pada kami, pakaian kembali bersih dan wangi.
                </p>
                <div class="flex gap-4">
                    {{-- LOGIKA BARU: Jika Login -> Mulai, Jika Tamu -> Login --}}
                    @auth
                        <a href="/layanan" class="px-8 py-4 rounded-2xl bg-white text-brand-600 font-bold shadow-lg hover:bg-brand-50 hover:-translate-y-1 transition-all flex items-center gap-2 group/btn">
                            <span>Mulai Mencuci</span>
                            <i class="ph-bold ph-arrow-right group-hover/btn:translate-x-1 transition-transform"></i>
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white/70 backdrop-blur-xl border border-white/60 rounded-[2.5rem] p-8 shadow-glass flex flex-col justify-between relative overflow-hidden"
             data-aos="fade-left" data-aos-delay="200">
            
            <div class="absolute -top-20 -right-20 w-60 h-60 bg-fresh-400/20 rounded-full blur-3xl pointer-events-none"></div>
            
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div>
                        <h3 class="font-bold text-slate-800 text-xl">Progres Diskon</h3>
                        <p class="text-slate-500 text-sm font-medium">Kumpulkan poin untuk cuci gratis.</p>
                    </div>
                </div>
            </div>

            <div class="my-8 relative z-10">
                <div class="flex justify-between items-end mb-3">
                    {{-- LOGIKA BARU: Default 0 jika Tamu --}}
                    <span class="text-5xl font-extrabold text-slate-800 tracking-tight">{{ Auth::user()->progres_kg ?? 0 }}<span class="text-xl text-slate-400 font-semibold">/8kg</span></span>
                    
                    @if(Auth::check() && Auth::user()->bonus > 0)
                        <span class="px-3 py-1.5 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold flex items-center gap-1 shadow-sm animate-bounce">
                            <i class="ph-fill ph-ticket"></i> {{ Auth::user()->bonus }} Tiket Bonus!
                        </span>
                    @endif
                </div>
                
                <div class="w-full h-5 bg-slate-100/80 rounded-full overflow-hidden border border-slate-200/80 shadow-inner p-1">
                    {{-- LOGIKA BARU: Hitung persentase aman untuk tamu --}}
                    @php 
                        $kg = Auth::user()->progres_kg ?? 0;
                        $width = min(($kg / 8) * 100, 100);
                    @endphp
                    <div x-data="{ width: 0 }"
                         x-init="setTimeout(() => width = {{ $width }}, 800)"
                         class="h-full rounded-full transition-all duration-[2000ms] ease-out bg-gradient-to-r from-brand-400 via-brand-500 to-fresh-400 shadow-[0_2px_10px_rgba(59,130,246,0.4)] relative overflow-hidden"
                         :style="`width: ${width}%`">
                         <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-transparent via-white/40 to-transparent -translate-x-full animate-[shimmer_2s_infinite]"></div>
                    </div>
                </div>
            </div>

            <div class="bg-brand-50/80 rounded-2xl p-4 border border-brand-100/50 text-sm text-brand-700 font-medium flex gap-3 items-start relative z-10">
                <i class="ph-fill ph-info text-brand-500 text-lg mt-0.5"></i>
                <p>Capai <span class="font-bold">8 Kg</span> akumulasi cucian untuk mendapatkan <span class="font-bold underline">Gratis 1 Kg</span> pada pesanan berikutnya.</p>
            </div>
        </div>
    </div>

    <div data-aos="fade-up" data-aos-offset="200">
        <div class="text-center mb-12">
             <span class="text-brand-600 font-bold text-sm uppercase tracking-wider">Kenapa Memilih Kami?</span>
             <h2 class="text-3xl font-bold text-slate-900 mt-2">Layanan Premium, Hasil Maksimal.</h2>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="p-8 rounded-[2.5rem] bg-white/70 backdrop-blur-md border border-slate-100 shadow-glass hover:shadow-glow hover:-translate-y-2 transition-all duration-300 group"
                 data-aos="zoom-in" data-aos-delay="0">
                <div class="w-16 h-16 rounded-3xl bg-blue-50 text-brand-600 flex items-center justify-center text-4xl mb-6 group-hover:bg-brand-600 group-hover:text-white transition-colors shadow-sm">
                    <i class="ph-duotone ph-moped"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-3">Antar Jemput Cepat</h3>
                <p class="text-slate-500 text-base leading-relaxed font-medium">Kurir kami siap menjemput dan mengantar kembali pakaian Anda tepat waktu, tanpa ribet.</p>
            </div>
            
            <div class="p-8 rounded-[2.5rem] bg-white/70 backdrop-blur-md border border-slate-100 shadow-glass hover:shadow-glow hover:-translate-y-2 transition-all duration-300 group"
                 data-aos="zoom-in" data-aos-delay="200">
                <div class="w-16 h-16 rounded-3xl bg-cyan-50 text-fresh-500 flex items-center justify-center text-4xl mb-6 group-hover:bg-fresh-500 group-hover:text-white transition-colors shadow-sm">
                    <i class="ph-duotone ph-sparkle"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-3">Teknologi Higienis</h3>
                <p class="text-slate-500 text-base leading-relaxed font-medium">Menggunakan deterjen ramah lingkungan dan steam finish untuk membunuh kuman.</p>
            </div>
            
            <div class="p-8 rounded-[2.5rem] bg-white/70 backdrop-blur-md border border-slate-100 shadow-glass hover:shadow-glow hover:-translate-y-2 transition-all duration-300 group"
                 data-aos="zoom-in" data-aos-delay="400">
                <div class="w-16 h-16 rounded-3xl bg-purple-50 text-purple-600 flex items-center justify-center text-4xl mb-6 group-hover:bg-purple-600 group-hover:text-white transition-colors shadow-sm">
                    <i class="ph-duotone ph-clock-countdown"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-3">Jaminan Tepat Waktu</h3>
                <p class="text-slate-500 text-base leading-relaxed font-medium">Proses pengerjaan terukur. Kami menghargai waktu Anda dengan layanan yang efisien.</p>
            </div>
        </div>
    </div>

    <div id="about" class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center scroll-mt-32">
        
        <div class="relative group" data-aos="fade-right">
            <div class="rounded-[3rem] overflow-hidden border border-slate-100 shadow-glass relative z-10">
               <img src="https://images.unsplash.com/photo-1582735689369-4fe89db7114c?q=80&w=1080&auto=format&fit=crop" 
               alt="Ni Laundry Service"
               loading="lazy"
                class="w-full h-[500px] object-cover hover:scale-105 transition-transform duration-1000">
            </div>

            <div class="absolute -top-10 -left-10 w-40 h-40 bg-brand-50 rounded-full blur-3xl -z-0"></div>
            <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-fresh-50 rounded-full blur-3xl -z-0"></div>

            <div class="absolute bottom-8 left-8 right-8 z-20 bg-white/80 backdrop-blur-md p-6 rounded-[2rem] border border-white/60 shadow-lg flex items-center gap-4 animate-float">
                <div>
                    <h4 class="font-bold text-slate-900 text-sm">100% Garansi Bersih</h4>
                    <p class="text-xs text-slate-500 font-medium">Jika tidak bersih, kami cuci ulang gratis.</p>
                </div>
            </div>
        </div>

        <div data-aos="fade-left" data-aos-delay="200">
            <span class="inline-block px-3 py-1 rounded-full bg-brand-50 text-brand-600 font-bold text-[10px] uppercase tracking-wider border border-brand-100 mb-4">
                Tentang Ni Laundry
            </span>
            <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-6 leading-tight">
                Lebih Dari Sekadar<br>Mencuci Pakaian.
            </h2>
            <p class="text-slate-500 text-lg leading-relaxed mb-8">
                Kami percaya bahwa pakaian yang bersih memberikan kepercayaan diri. Ni Laundry hadir dengan misi menyederhanakan hidup Anda melalui layanan perawatan pakaian berstandar profesional.
            </p>
            </div>
        </div>
    </div>

    <div class="rounded-[3rem] bg-slate-900 overflow-hidden relative p-12 md:p-20 text-center group shadow-2xl"
         data-aos="zoom-in-up" data-aos-offset="150">
         
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] group-hover:scale-110 transition-transform duration-[2000ms]"></div>
        <div class="absolute -top-1/2 -left-1/2 w-full h-full bg-brand-500/20 blur-[150px] rounded-full pointer-events-none"></div>
        
        <div class="relative z-10 max-w-3xl mx-auto">
            <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-6 leading-tight">
                Siap Merasakan<br>Perbedaannya?
            </h2>
            <p class="text-slate-300 text-xl mb-10 font-medium leading-relaxed">
                Nikmati kemudahan layanan laundry premium dalam satu genggaman. Hemat waktu, tenaga, dan biaya.
            </p>
            <a href="/layanan" class="inline-flex px-10 py-5 rounded-2xl bg-white text-slate-900 font-bold text-lg hover:scale-105 hover:shadow-glow transition-all group/cta">
                <span>Pesan Sekarang Juga</span>
                <i class="ph-bold ph-arrow-right ml-2 group-hover/cta:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>

</div>

<style>
    @keyframes shimmer { 100% { transform: translateX(100%); } }
</style>
@endsection