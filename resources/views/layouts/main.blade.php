<!DOCTYPE html>
<html lang="id"> 
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    {{-- Preload JS utama untuk memutus critical chain --}}
    <link rel="modulepreload" href="{{ Vite::asset('resources/js/app.js') }}">
    
    {{-- Cukup panggil @vite satu kali saja --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>@yield('title', 'Ni Laundry')</title>

    {{-- Preconnect hanya untuk yang benar-benar masih pakai CDN --}}
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://unpkg.com">

    <style> 
        [x-cloak] { display: none !important; }
        .footer-texture {
            background-image: url("{{ asset('img/cubes.png') }}");
        }
    </style>

    <meta name="description" content="Jasa laundry kiloan dan satuan terbaik dengan teknologi modern.">
    <meta name="theme-color" content="#0f172a">
    <link rel="icon" href="{{ asset('img/logo.webp') }}" type="image/webp">
</head>

<body class="text-slate-600 antialiased font-sans flex flex-col min-h-screen">

    <div id="preloader" role="status" class="fixed inset-0 z-[9999] bg-slate-900 flex flex-col items-center justify-center">
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
                <a href="/" class="flex items-center gap-3 group">
                    <img src="{{ asset('img/logo.webp') }}" 
                         srcset="{{ asset('img/logo.webp') }} 2x" 
                         alt="Logo Ni Laundry" 
                         width="40" height="40" 
                         class="h-10 w-10 object-contain">
                    <span class="text-xl md:text-2xl font-bold text-slate-800 tracking-tight transition-colors"
                          :class="scrolled ? 'text-slate-800' : 'text-slate-900'">
                        Ni Laundry<span class="text-fresh-500">.</span>
                    </span>
                </a>

                {{-- Desktop Menu --}}
                <div class="hidden md:flex items-center gap-1 bg-white/50 p-1.5 rounded-full border border-white/50 backdrop-blur-sm shadow-sm">
                    @if(Auth::guard('admin')->check())
                        <a href="/admin/dashboard" class="px-4 py-2 rounded-full text-sm font-bold hover:bg-slate-100">Dashboard</a>
                        <a href="/admin/pesanan" class="px-4 py-2 rounded-full text-sm font-bold hover:bg-slate-100">Pesanan</a>
                    @else 
                        <a href="/" class="px-5 py-2 rounded-full text-sm font-semibold transition-all {{ Request::is('/') ? 'bg-white text-brand-600 shadow-sm' : 'text-slate-500 hover:text-brand-600' }}">Beranda</a> 
                        <a href="/layanan" class="px-5 py-2 rounded-full text-sm font-semibold transition-all {{ Request::is('layanan') ? 'bg-white text-brand-600 shadow-sm' : 'text-slate-500 hover:text-brand-600' }}">Layanan</a>
                        @auth
                            <a href="/riwayat" class="px-5 py-2 rounded-full text-sm font-semibold transition-all {{ Request::is('riwayat') ? 'bg-white text-brand-600 shadow-sm' : 'text-slate-500 hover:text-brand-600' }}">Riwayat</a>
                        @endauth
                    @endif
                </div>

                <div class="hidden md:flex items-center gap-4">
                    @if(Auth::check() || Auth::guard('admin')->check())
                        <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                                class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center hover:bg-red-50 text-slate-600 hover:text-red-500 transition-all shadow-sm">
                            <i class="ph-bold ph-sign-out text-xl"></i>
                        </button>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                    @else
                        <a href="/login" class="px-6 py-2.5 rounded-xl bg-slate-900 text-white text-sm font-bold hover:bg-brand-600 transition-all shadow-lg">
                            Login
                        </a>
                    @endif
                </div>

                <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 text-slate-800">
                    <i class="ph-bold text-2xl" :class="mobileOpen ? 'ph-x' : 'ph-list'"></i>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileOpen" x-collapse x-cloak class="md:hidden absolute top-full left-0 w-full px-4 mt-2">
            <div class="bg-white/95 backdrop-blur-xl rounded-2xl shadow-xl border border-white/50 p-4 flex flex-col gap-2">
                <a href="/" class="p-3 font-medium text-slate-700">Beranda</a>
                <a href="/layanan" class="p-3 font-medium text-slate-700">Layanan</a>
                @auth <a href="/riwayat" class="p-3 font-medium text-slate-700">Riwayat</a> @endauth
                <div class="h-px bg-slate-100 my-1"></div>
                @if(Auth::check() || Auth::guard('admin')->check())
                     <button onclick="document.getElementById('logout-form').submit();" class="p-3 font-bold text-red-500 bg-red-50 rounded-xl">Logout</button>
                @else
                    <a href="/login" class="p-3 font-bold bg-slate-900 text-white text-center rounded-xl">Login Member</a>
                @endif
            </div>
        </div>
    </nav>

    <main id="main-content" class="flex-grow pt-32 pb-12 px-4 md:px-8 max-w-7xl mx-auto w-full z-10 relative">
        @yield('content')
    </main>

    <footer class="bg-slate-900 text-slate-300 mt-20 pt-20 pb-10 rounded-t-[3rem] relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 footer-texture"></div>
        <div class="max-w-7xl mx-auto px-8 relative z-10 text-center text-xs text-slate-500">
            <p>© 2025 Ni Laundry. All rights reserved.</p>
        </div>
    </footer>

    {{-- SCRIPTS --}}
    {{-- Alpine sudah dihapus dari sini karena sudah masuk ke app.js via NPM --}}
    <script defer src="https://unpkg.com/@phosphor-icons/web"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.4/gsap.min.js"></script>
    
    <script>
        window.addEventListener('load', () => {
            const preloader = document.getElementById('preloader');
            if (sessionStorage.getItem('introShown')) {
                if(preloader) preloader.style.display = 'none';
            } else {
                if(typeof gsap !== 'undefined' && preloader) {
                    const tl = gsap.timeline({
                        onComplete: () => { 
                            preloader.style.display = 'none';
                            sessionStorage.setItem('introShown', 'true');
                        }
                    });
                    tl.to("#loader-bar", { width: "100%", duration: 0.8, ease: "power2.inOut" })
                      .to("#preloader", { opacity: 0, duration: 0.5 });
                }
            }
        });
    </script>
</body>
</html>