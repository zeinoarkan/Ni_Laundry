<!DOCTYPE html>
<html lang="id"> 
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title>@yield('title', 'Ni Laundry')</title>
    <style> 
        [x-cloak] { display: none !important; }
    </style>

    <meta name="description" content="Jasa laundry kiloan dan satuan terbaik dengan teknologi modern.">
    <meta name="theme-color" content="#0f172a">

    {{-- Pastikan logo ini sudah di-resize jadi 100x100px (file size kecil) --}}
    <link rel="icon" href="{{ asset('img/logo.webp') }}" type="image/webp">
    
    {{-- 
        PERHATIAN: 
        Google Fonts & AOS CSS sudah dihapus dari sini.
        Pastikan kamu sudah melakukan "npm run build" setelah update app.css & app.js 
    --}}
    
    {{-- VITE DIRECTIVE (Memuat Font Outfit, AOS, Tailwind, JS Logic) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="text-slate-600 antialiased font-sans flex flex-col min-h-screen">

    <div id="preloader" role="status">
        <div class="flex items-center gap-3 animate-pulse">
            <span class="text-3xl md:text-5xl font-bold tracking-tight text-white">
                Ni Laundry<span class="text-fresh-500">.</span>
            </span>
        </div>
        <div class="mt-6 w-40 md:w-64 h-1 bg-slate-800 rounded-full overflow-hidden">
            <div id="loader-bar" class="h-full bg-brand-500 w-0"></div>
        </div>
    </div>

    <script>
        if (sessionStorage.getItem('introShown')) {
            document.getElementById('preloader').style.display = 'none';
        }
    </script>

    <nav x-data="{ scrolled: false, mobileOpen: false }" 
         @scroll.window="scrolled = (window.pageYOffset > 20)"
         class="fixed top-0 w-full z-50 transition-all duration-300 px-4 md:px-8"
         :class="scrolled ? 'py-3' : 'py-6'">
        
        <div class="max-w-7xl mx-auto rounded-2xl transition-all duration-300 border border-transparent"
             :class="scrolled ? 'bg-white/80 backdrop-blur-lg shadow-glass border-white/40 px-4 py-2' : 'bg-transparent px-2'">
            
            <div class="flex justify-between items-center">
                
                {{-- LOGO --}}
                <a href="/" class="flex items-center gap-3 group">
                    <img src="{{ asset('img/logo.webp') }}" alt="Logo" width="40" height="40" class="h-10 w-10 object-contain">
                    <span class="text-xl md:text-2xl font-bold text-slate-800 tracking-tight">
                        Ni Laundry<span class="text-fresh-500">.</span>
                    </span>
                </a>

                {{-- DESKTOP MENU --}}
                <div class="hidden md:flex items-center gap-1 bg-white/50 p-1.5 rounded-full border border-white/50 backdrop-blur-sm">
                    @if(Auth::guard('admin')->check())
                        <a href="/admin/dashboard" class="p-3 rounded-xl font-bold hover:bg-slate-100">Dashboard</a>
                        <a href="/admin/pesanan" class="p-3 rounded-xl font-bold hover:bg-slate-100">Kelola Pesanan</a>
                        <a href="/admin/layanan" class="p-3 rounded-xl font-bold hover:bg-slate-100">Kelola Layanan</a>
                        <a href="/admin/diskon" class="p-3 rounded-xl font-bold hover:bg-slate-100">Monitoring Diskon</a>
                        <a href="/admin/users" class="p-3 rounded-xl font-bold hover:bg-slate-100">Kelola Admin</a>
                    @else 
                        <a href="/" class="px-5 py-2 rounded-full text-sm font-semibold transition-all {{ Request::is('/') ? 'bg-white text-brand-600 shadow-sm' : 'text-slate-500 hover:text-brand-600' }}">Beranda</a> 
                        <a href="/layanan" class="px-5 py-2 rounded-full text-sm font-semibold transition-all {{ Request::is('layanan') ? 'bg-white text-brand-600 shadow-sm' : 'text-slate-500 hover:text-brand-600' }}">Layanan</a>
                        @auth
                            <a href="/riwayat" class="px-5 py-2 rounded-full text-sm font-semibold transition-all {{ Request::is('riwayat') ? 'bg-white text-brand-600 shadow-sm' : 'text-slate-500 hover:text-brand-600' }}">Riwayat</a>
                        @endauth
                    @endif
                </div>

                {{-- RIGHT SIDE (LOGIN/USER PROFILE) --}}
                <div class="hidden md:flex items-center gap-4">
                    @if(Auth::check() || Auth::guard('admin')->check())
                        <div class="text-right hidden lg:block">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Hi,</span>
                            <span class="block text-sm font-bold text-slate-800">
                                {{ Auth::guard('admin')->check() ? Auth::guard('admin')->user()->username : Auth::user()->nama }}
                            </span>
                        </div>
                        
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center hover:bg-red-50 text-slate-600 hover:text-red-500 transition-all" title="Logout">
                            <i class="ph-bold ph-sign-out text-xl"></i>
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>

                    @else
                        <a href="/login" class="px-6 py-2.5 rounded-xl bg-slate-900 text-white text-sm font-bold hover:bg-brand-600 transition-colors shadow-lg hover:shadow-glow hover:-translate-y-0.5 transform duration-300">
                            Login
                        </a>
                    @endif
                </div>

                {{-- MOBILE HAMBURGER BUTTON --}}
                <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 text-slate-800">
                    <i class="ph-bold text-2xl" :class="mobileOpen ? 'ph-x' : 'ph-list'"></i>
                </button>
            </div>
        </div>

        {{-- MOBILE MENU --}}
        <div x-show="mobileOpen" x-collapse x-cloak class="md:hidden absolute top-full left-0 w-full px-4 mt-2">
            <div class="bg-white/95 backdrop-blur-xl rounded-2xl shadow-xl border border-white/50 p-4 flex flex-col gap-3">
                
                <a href="/" class="p-3 font-medium text-slate-700 {{ Request::is('/') ? 'bg-brand-50 text-brand-600 rounded-xl' : '' }}">Beranda</a>
                <a href="/layanan" class="p-3 font-medium text-slate-700 {{ Request::is('layanan') ? 'bg-brand-50 text-brand-600 rounded-xl' : '' }}">Layanan</a>
                @auth
                    <a href="/riwayat" class="p-3 font-medium text-slate-700 {{ Request::is('riwayat') ? 'bg-brand-50 text-brand-600 rounded-xl' : '' }}">Riwayat</a>
                @endauth

                <div class="h-px bg-slate-100 my-1"></div>

                @if(Auth::check() || Auth::guard('admin')->check())
                     <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="p-3 font-bold text-red-500 bg-red-50 rounded-xl text-center">Logout</a>
                @else
                    <a href="/login" class="p-3 font-bold bg-slate-900 text-white text-center rounded-xl shadow-lg">Login Member</a>
                    <a href="/register" class="p-3 font-bold text-slate-700 border border-slate-200 text-center rounded-xl">Daftar Akun</a>
                @endif
            </div>
        </div>
    </nav>

    <main id="main-content" class="flex-grow pt-32 pb-12 px-4 md:px-8 max-w-7xl mx-auto w-full z-10 relative">
        @if(session('success'))
            {{-- Alert sudah bagus, pakai AlpineJS --}}
            <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center gap-3 text-emerald-700 shadow-sm" data-aos="fade-down">
                <i class="ph-fill ph-check-circle text-xl"></i>
                <span class="font-medium text-sm">{{ session('success') }}</span>
                <button @click="show = false" class="ml-auto"><i class="ph-bold ph-x"></i></button>
            </div>
        @endif
        @yield('content')
    </main>

    <footer class="bg-slate-900 text-slate-300 mt-20 pt-20 pb-10 rounded-t-[3rem] relative overflow-hidden">
        {{-- Background Footer --}}
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-brand-500/20 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-fresh-500/10 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-8 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-12 mb-16">
                <div class="lg:col-span-4 space-y-6">
                    <a href="/" class="flex items-center gap-2 group w-fit">
                        {{-- Optimasi Logo Footer --}}
                        <img src="{{ asset('img/logo.webp') }}" alt="Logo Ni Laundry" width="40" height="40" class="h-10 w-auto object-contain transition-transform duration-300 group-hover:scale-105">
                        <span class="text-2xl font-bold tracking-tight">
                            Ni Laundry<span class="text-fresh-400">.</span>
                        </span>
                    </a>
                    <p class="text-slate-400 text-sm leading-relaxed max-w-sm">
                        Layanan laundry dengan teknologi modern. Kami merawat pakaian Anda dengan standar kebersihan internasional dan pelayanan sepenuh hati.
                    </p>
                    <div class="flex gap-3">
                        <a href="https://www.instagram.com/ni.laundry" target="_blank" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:bg-brand-600 hover:text-white hover:border-brand-600 transition-all"><i class="ph-fill ph-instagram-logo text-lg"></i></a>
                        <a href="https://web.facebook.com/profile.php?id=61582451486766#" target="_blank" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:bg-brand-600 hover:text-white hover:border-brand-600 transition-all"><i class="ph-fill ph-facebook-logo text-lg"></i></a>
                        <a href="https://wa.me/+6282147556964" target="_blank" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:bg-brand-600 hover:text-white hover:border-brand-600 transition-all"><i class="ph-fill ph-whatsapp-logo text-lg"></i></a>
                    </div>
                </div>
                {{-- Link Footer Lainnya (Tetap sama) --}}
                <div class="lg:col-span-2 space-y-6">
                    <h4 class="font-bold text-lg">Layanan</h4>
                    <ul class="space-y-4 text-sm text-slate-400">
                        <li><a href="/layanan" class="hover:text-brand-400 transition-colors flex items-center gap-2 group"><i class="ph-bold ph-caret-right opacity-0 group-hover:opacity-100 transition-opacity -ml-4 group-hover:ml-0"></i> Cuci Kiloan</a></li>
                        <li><a href="/layanan" class="hover:text-brand-400 transition-colors flex items-center gap-2 group"><i class="ph-bold ph-caret-right opacity-0 group-hover:opacity-100 transition-opacity -ml-4 group-hover:ml-0"></i> Cuci Satuan</a></li>
                        <li><a href="/layanan" class="hover:text-brand-400 transition-colors flex items-center gap-2 group"><i class="ph-bold ph-caret-right opacity-0 group-hover:opacity-100 transition-opacity -ml-4 group-hover:ml-0"></i> Cuci Khusus</a></li>
                    </ul>
                </div>
                <div class="lg:col-span-2 space-y-6">
                    <h4 class="font-bold text-lg">Perusahaan</h4>
                    <ul class="space-y-4 text-sm text-slate-400">
                        <li><a href="/#about" class="hover:text-brand-400 transition-colors">Tentang Kami</a></li>
                        <li><a href="https://www.google.com/maps?q=-7.7766983,110.3455633&z=17&hl=en" target="_blank" class="hover:text-brand-400 transition-colors flex items-center gap-2 group">Lokasi Outlet <i class="ph-bold ph-arrow-square-out opacity-0 group-hover:opacity-100 transition-opacity text-xs"></i></a></li>
                        <li><a href="https://wa.me/+6282147556964" target="_blank" class="hover:text-brand-400 transition-colors flex items-center gap-2 group">Kontak Kami <i class="ph-bold ph-arrow-square-out opacity-0 group-hover:opacity-100 transition-opacity text-xs"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-slate-500">
                <p>© 2025 Ni Laundry. All rights reserved.</p>
            </div>
        </div>
    </footer>

    {{-- SCRIPTS --}}
    {{-- Alpine & Phosphor tetap CDN (ringan/tidak masalah) --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/@phosphor-icons/web"></script>
    
    {{-- GSAP tetap CDN untuk menjalankan script preloader di bawah --}}
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.4/gsap.min.js"></script>
    
    {{-- NOTE: Script AOS CDN sudah DIHAPUS karena sudah jalan via app.js --}}

    <script>
        // Safety Timeout
        setTimeout(() => {
            const p = document.getElementById('preloader');
            if(p && p.style.display !== 'none') p.style.display = 'none';
        }, 5000);

        window.addEventListener('load', () => {
            // 1. Init AOS -> SUDAH DIPINDAH KE APP.JS (Disini dihapus)

            // 2. Lenis -> SUDAH DI APP.JS

            // 3. Init GSAP Preloader
            const preloader = document.getElementById('preloader');
            if (!sessionStorage.getItem('introShown')) {
                if(typeof gsap !== 'undefined') {
                    const tl = gsap.timeline({
                        onComplete: () => { 
                            preloader.style.display = 'none';
                            sessionStorage.setItem('introShown', 'true');
                        }
                    });
                    tl.to("#loader-bar", { width: "100%", duration: 1.0, ease: "power2.inOut" })
                      .to("#preloader", { yPercent: -100, duration: 0.8, ease: "power4.inOut", delay: 0.1 });
                } else {
                    preloader.style.display = 'none';
                }
            } else {
                preloader.style.display = 'none';
            }
        });
    </script>
</body>
</html>