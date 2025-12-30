<!DOCTYPE html>
<html lang="id"> <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title>@yield('title', 'Ni Laundry')</title>
    <meta name="description" content="Jasa laundry kiloan dan satuan terbaik dengan teknologi modern.">
    <meta name="theme-color" content="#0f172a">

    <link rel="icon" href="{{ asset('img/logo.webp') }}" type="image/webp">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Outfit"', 'sans-serif'] },
                    fontSize: {
                        'fluid-h1': 'clamp(1.75rem, 4vw + 1rem, 4rem)', 
                    },
                    colors: {
                        // Warna ini WAJIB sama dengan yang dipakai di Login/Register
                        brand: { 50: '#eff6ff', 100: '#dbeafe', 200: '#bfdbfe', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8', 900: '#1e3a8a' },
                        fresh: { 400: '#22d3ee', 500: '#06b6d4' }
                    },
                    boxShadow: {
                        'glass': '0 8px 32px 0 rgba(31, 38, 135, 0.07)',
                        'glow': '0 0 20px rgba(37, 99, 235, 0.5)', // Efek glow biru
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        
        body {
            background-color: #F8FAFC;
            background-image: 
                radial-gradient(at 0% 0%, hsla(213,100%,88%,1) 0, transparent 50%), 
                radial-gradient(at 100% 100%, hsla(189,100%,88%,1) 0, transparent 50%);
            background-attachment: fixed;
            -webkit-font-smoothing: antialiased; /* Teks tajam di Mac/iPhone */
            -moz-osx-font-smoothing: grayscale;
            overflow-x: hidden;
        }

        /* Preloader */
        #preloader {
            position: fixed;
            inset: 0;
            background: #0f172a;
            z-index: 99999;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            will-change: transform;
        }
        
        /* Lenis Recommended CSS */
        html.lenis { height: auto; }
        .lenis.lenis-smooth { scroll-behavior: auto !important; }
        .lenis.lenis-smooth [data-lenis-prevent] { overscroll-behavior: contain; }
        .lenis.lenis-stopped { overflow: hidden; }
        .lenis.lenis-scrolling iframe { pointer-events: none; }
    </style>
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

    <nav x-data="{ scrolled: false, mobileOpen: false }" 
         @scroll.window="scrolled = (window.pageYOffset > 20)"
         class="fixed top-0 w-full z-50 transition-all duration-300 px-4 md:px-8"
         :class="scrolled ? 'py-3' : 'py-6'">
        
        <div class="max-w-7xl mx-auto rounded-2xl transition-all duration-300 border border-transparent"
             :class="scrolled ? 'bg-white/80 backdrop-blur-lg shadow-glass border-white/40 px-4 py-2' : 'bg-transparent px-2'">
            
            <div class="flex justify-between items-center">
                
                <a href="/" class="flex items-center gap-3 group">
                    <img src="{{ asset('img/logo.webp') }}" alt="Logo" width="40" height="40" class="h-10 w-10 object-contain">
                    <span class="text-xl md:text-2xl font-bold text-slate-800 tracking-tight">
                        Ni Laundry<span class="text-fresh-500">.</span>
                    </span>
                </a>

                <div class="hidden md:flex items-center gap-1 bg-white/50 p-1.5 rounded-full border border-white/50 backdrop-blur-sm">
                    @if(Auth::guard('admin')->check())
                        <a href="/admin/dashboard" class="p-3 rounded-xl font-bold hover:bg-slate-100">Dashboard</a>
                        <a href="/admin/pesanan" class="p-3 rounded-xl font-bold hover:bg-slate-100">Kelola Pesanan</a>
                        <a href="/admin/layanan" class="p-3 rounded-xl font-bold hover:bg-slate-100">Kelola Layanan</a>
                        <a href="/admin/diskon" class="p-3 rounded-xl font-bold hover:bg-slate-100">Monitoring Diskon</a>
                        <a href="/admin/users" class="p-3 rounded-xl font-bold hover:bg-slate-100">Kelola Admin</a>
                        <a href="/logout" class="p-3 rounded-xl font-bold text-red-500 bg-red-50 mt-2">Logout</a>
                    @else 
                        @auth
                            <a href="/" class="px-5 py-2 rounded-full text-sm font-semibold transition-all {{ Request::is('/') ? 'bg-white text-brand-600 shadow-sm' : 'text-slate-500 hover:text-brand-600' }}">Beranda</a> 
                            <a href="/layanan" class="px-5 py-2 rounded-full text-sm font-semibold transition-all {{ Request::is('layanan') ? 'bg-white text-brand-600 shadow-sm' : 'text-slate-500 hover:text-brand-600' }}">Layanan</a>
                            <a href="/riwayat" class="px-5 py-2 rounded-full text-sm font-semibold transition-all {{ Request::is('riwayat') ? 'bg-white text-brand-600 shadow-sm' : 'text-slate-500 hover:text-brand-600' }}">Riwayat</a>
                            <a href="/logout" class="p-3 rounded-xl font-medium text-red-500 bg-red-50 mt-2 md:hidden">Logout</a>
                        @endauth
                    @endif
                </div>

                <div class="hidden md:flex items-center gap-4">
                    @if(Auth::check() || Auth::guard('admin')->check())
                        <div class="text-right hidden lg:block">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Hi,</span>
                            <span class="block text-sm font-bold text-slate-800">
                                {{ Auth::guard('admin')->check() ? Auth::guard('admin')->user()->username : Auth::user()->nama }}
                            </span>
                        </div>
                        <a href="/logout" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center hover:bg-red-50 text-slate-600 hover:text-red-500 transition-all">
                            <i class="ph-bold ph-sign-out text-xl"></i>
                        </a>
                    @else
                        <a href="/login" class="px-6 py-2.5 rounded-xl bg-slate-900 text-white text-sm font-bold hover:bg-brand-600 transition-colors shadow-lg hover:shadow-glow hover:-translate-y-0.5 transform duration-300">
                            Login
                        </a>
                    @endif
                </div>

                <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 text-slate-800">
                    <i class="ph-bold text-2xl" :class="mobileOpen ? 'ph-x' : 'ph-list'"></i>
                </button>
            </div>
        </div>

        <div x-show="mobileOpen" x-collapse x-cloak class="md:hidden absolute top-full left-0 w-full px-4 mt-2">
            <div class="bg-white/95 backdrop-blur-xl rounded-2xl shadow-xl border border-white/50 p-4 flex flex-col gap-3">
                auth
                <a href="/" class="p-3 font-medium text-slate-700">Beranda</a>
                <a href="/layanan" class="p-3 font-medium text-slate-700">Layanan</a>
                <a href="/riwayat" class="p-3 font-medium text-slate-700">Riwayat</a>
                endauth
                @if(Auth::check() || Auth::guard('admin')->check())
                     <a href="/logout" class="p-3 font-bold text-red-500 bg-red-50 rounded-xl">Logout</a>
                @else
                    <a href="/login" class="p-3 font-bold bg-slate-900 text-white text-center rounded-xl">Login</a>
                @endif
            </div>
        </div>
    </nav>

    <main id="main-content" class="flex-grow pt-32 pb-12 px-4 md:px-8 max-w-7xl mx-auto w-full z-10 relative">
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center gap-3 text-emerald-700 shadow-sm">
                <i class="ph-fill ph-check-circle text-xl"></i>
                <span class="font-medium text-sm">{{ session('success') }}</span>
            </div>
        @endif
        @yield('content')
    </main>

    <footer class="bg-slate-900 text-slate-300 mt-20 pt-20 pb-10 rounded-t-[3rem] relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-brand-500/20 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-fresh-500/10 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-8 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-12 mb-16">
                <div class="lg:col-span-4 space-y-6">
                    <a href="/" class="flex items-center gap-2 group w-fit">
                        <img src="{{ asset('img/logo.webp') }}" alt="Logo Ni Laundry" class="h-10 w-auto object-contain transition-transform duration-300 group-hover:scale-105">
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
                <p>&copy; 2025 Ni Laundry. All rights reserved.</p>
            </div>

        </div>
    </footer>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/@phosphor-icons/web"></script>
    <script defer src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.4/gsap.min.js"></script>
    <script defer src="https://unpkg.com/@studio-freight/lenis@1.0.29/dist/lenis.min.js"></script>

    <script>
        // Safety Timeout untuk Preloader
        setTimeout(() => {
            const p = document.getElementById('preloader');
            if(p) p.style.display = 'none';
        }, 3500);

        window.addEventListener('load', () => {
            // 1. Init AOS
            if(typeof AOS !== 'undefined') AOS.init({ once: true, duration: 600, offset: 50 });

            // 2. Init Lenis (Smooth Scroll)
            if(typeof Lenis !== 'undefined') {
                const lenis = new Lenis({
                    duration: 1.2,
                    easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)), // Easing function
                    smooth: true,
                });
                function raf(time) {
                    lenis.raf(time);
                    requestAnimationFrame(raf);
                }
                requestAnimationFrame(raf);
            }

            // 3. Init GSAP Preloader
            if(typeof gsap !== 'undefined') {
                const tl = gsap.timeline({
                    onComplete: () => { document.getElementById('preloader').style.display = 'none'; }
                });
                tl.to("#loader-bar", { width: "100%", duration: 1.0, ease: "power2.inOut" })
                  .to("#preloader", { yPercent: -100, duration: 0.8, ease: "power4.inOut", delay: 0.1 });
            } else {
                document.getElementById('preloader').style.display = 'none';
            }
        });
    </script>
</body>
</html>