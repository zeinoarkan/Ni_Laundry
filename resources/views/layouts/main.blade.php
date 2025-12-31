<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ni Laundry</title>
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">
   
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
   
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        [x-cloak] { display: none !important; }
        body {
            background-color: #F8FAFC;
            background-image:
                radial-gradient(at 0% 0%, hsla(213,100%,88%,1) 0, transparent 50%),
                radial-gradient(at 100% 100%, hsla(189,100%,88%,1) 0, transparent 50%);
            background-attachment: fixed;
        }
    </style>
</head>
<body class="text-slate-600 antialiased font-sans flex flex-col min-h-screen overflow-x-hidden">

    <nav x-data="{ scrolled: false, mobileOpen: false }"
         @scroll.window="scrolled = (window.pageYOffset > 20)"
         class="fixed top-0 w-full z-50 transition-all duration-300 px-4 md:px-0"
         :class="scrolled ? 'py-2' : 'py-4 md:py-6'">
       
        <div class="max-w-7xl mx-auto rounded-2xl transition-all duration-300 border border-transparent"
             :class="scrolled ? 'bg-white/80 backdrop-blur-lg shadow-glass border-white/40 px-4 md:px-6 py-3' : 'bg-transparent px-2 md:px-6'">
           
            <div class="flex justify-between items-center">
               
                <a href="/" class="flex items-center gap-2 md:gap-3 group shrink-0">
                    <img src="{{ asset('img/logo.png') }}" alt="Ni Laundry" class="h-8 md:h-10 w-auto object-contain transition-transform duration-300 group-hover:scale-105">
                   
                    <span class="text-xl md:text-2xl font-bold text-slate-800 tracking-tight group-hover:text-brand-600 transition-colors">
                        Ni Laundry<span class="text-fresh-500">.</span>
                    </span>
                </a>

               <div class="hidden lg:flex items-center gap-1 bg-white/50 p-1.5 rounded-full border border-white/50 backdrop-blur-sm">
                    @if(Auth::guard('admin')->check())
                        <a href="/admin/dashboard" class="px-5 py-2 rounded-full text-sm font-semibold transition-all {{ Request::is('admin/dashboard') ? 'bg-white text-brand-600 shadow-sm' : 'text-slate-500 hover:text-brand-600' }}">Dashboard</a>
                    @else
                        <a href="/" class="px-5 py-2 rounded-full text-sm font-semibold transition-all {{ Request::is('/') ? 'bg-white text-brand-600 shadow-sm' : 'text-slate-500 hover:text-brand-600' }}">Beranda</a>
                        <a href="/layanan" class="px-5 py-2 rounded-full text-sm font-semibold transition-all {{ Request::is('layanan') ? 'bg-white text-brand-600 shadow-sm' : 'text-slate-500 hover:text-brand-600' }}">Layanan</a>
                       
                        @auth
                            <a href="/riwayat" class="px-5 py-2 rounded-full text-sm font-semibold transition-all {{ Request::is('riwayat') ? 'bg-white text-brand-600 shadow-sm' : 'text-slate-500 hover:text-brand-600' }}">Riwayat</a>
                        @endauth
                    @endif
                </div>

                <div class="hidden lg:flex items-center gap-4">
                    @if(Auth::check() || Auth::guard('admin')->check())
                        <div class="flex items-center gap-3">
                            <div class="text-right hidden xl:block">
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Logged in as</span>
                                <span class="block text-sm font-bold text-slate-800">
                                    {{ Auth::guard('admin')->check() ? Auth::guard('admin')->user()->username : Auth::user()->nama }}
                                </span>
                            </div>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center hover:bg-red-50 hover:text-red-500 hover:border-red-200 transition-all shadow-sm" title="Logout">
                                    <i class="ph-bold ph-sign-out"></i>
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="/login" class="px-6 py-2.5 rounded-xl bg-slate-900 text-white text-sm font-bold hover:bg-brand-600 hover:shadow-glow hover:-translate-y-0.5 transition-all duration-300">
                            Login
                        </a>
                    @endif
                </div>

                <button @click="mobileOpen = !mobileOpen" class="lg:hidden p-2 text-slate-800 rounded-lg hover:bg-white/50 transition-colors">
                    <i class="ph-bold ph-list text-2xl"></i>
                </button>
            </div>
        </div>

        <div x-show="mobileOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             x-cloak
             @click.away="mobileOpen = false"
             class="lg:hidden absolute top-full left-0 w-full px-4 mt-2">
           
            <div class="bg-white/95 backdrop-blur-xl rounded-2xl shadow-xl border border-white/50 p-4 flex flex-col gap-2">
               
                @if(Auth::guard('admin')->check())
                    <div class="px-3 py-2 text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 mb-2">Admin Menu</div>
                    <a href="/admin/dashboard" class="p-3 rounded-xl font-bold hover:bg-slate-100 text-slate-700">Dashboard</a>
                    <a href="/admin/pesanan" class="p-3 rounded-xl font-bold hover:bg-slate-100 text-slate-700">Kelola Pesanan</a>
                    <a href="/admin/layanan" class="p-3 rounded-xl font-bold hover:bg-slate-100 text-slate-700">Kelola Layanan</a>
                    <a href="/admin/diskon" class="p-3 rounded-xl font-bold hover:bg-slate-100 text-slate-700">Monitoring Diskon</a>
                    <a href="/admin/users" class="p-3 rounded-xl font-bold hover:bg-slate-100 text-slate-700">Kelola Admin</a>
                    <form action="{{ route('logout') }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="w-full text-left p-3 rounded-xl font-bold text-red-500 bg-red-50 mt-2 hover:bg-red-100 transition-colors">
                            Logout
                        </button>
                    </form>

                @elseif(Auth::guard('web')->check())
                    <div class="px-3 py-2 mb-2 flex items-center justify-between border-b border-slate-100">
                        <span class="text-sm font-bold text-slate-800">{{ Auth::user()->nama }}</span>
                        <span class="text-[10px] bg-brand-50 text-brand-600 px-2 py-0.5 rounded-full font-bold">Member</span>
                    </div>
                    <a href="/" class="p-3 rounded-xl font-medium {{ Request::is('/') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50' }}">Beranda</a>
                    <a href="/layanan" class="p-3 rounded-xl font-medium {{ Request::is('layanan') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50' }}">Layanan</a>
                    <a href="/riwayat" class="p-3 rounded-xl font-medium {{ Request::is('riwayat') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50' }}">Riwayat</a>
                    <form action="{{ route('logout') }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="w-full text-left p-3 rounded-xl font-bold text-red-500 bg-red-50 mt-2 hover:bg-red-100 transition-colors">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="/login" class="p-3 rounded-xl font-bold bg-slate-900 text-white text-center shadow-lg mt-2">Login Member</a>
                @endif
            </div>
        </div>
    </nav>

    <main class="flex-grow pt-24 md:pt-32 pb-8 md:pb-12 px-4 sm:px-6 md:px-8 max-w-7xl mx-auto w-full z-10">
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition
                 class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center gap-3 text-emerald-700 shadow-sm">
                <i class="ph-fill ph-check-circle text-xl shrink-0"></i>
                <span class="font-medium text-sm">{{ session('success') }}</span>
            </div>
        @endif
        @yield('content')
    </main>

    <footer class="bg-slate-900 text-white mt-12 md:mt-20 pt-12 md:pt-20 pb-10 rounded-t-[2rem] md:rounded-t-[3rem] relative overflow-hidden">
       
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        <div class="absolute -top-24 -left-24 w-64 md:w-96 h-64 md:h-96 bg-brand-500/20 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="absolute bottom-0 right-0 w-64 md:w-96 h-64 md:h-96 bg-fresh-500/10 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 md:px-8 relative z-10">
           
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 md:gap-12 mb-12 md:mb-16">
               
                <div class="lg:col-span-4 space-y-6">
                    <a href="/" class="flex items-center gap-2 group w-fit">
                        <img src="{{ asset('img/logo.png') }}" alt="Ni Laundry" class="h-8 md:h-10 w-auto object-contain transition-transform duration-300 group-hover:scale-105">
                        <span class="text-xl md:text-2xl font-bold tracking-tight">
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

                <div class="lg:col-span-2 space-y-4 md:space-y-6">
                    <h4 class="font-bold text-lg">Layanan</h4>
                    <ul class="space-y-3 md:space-y-4 text-sm text-slate-400">
                        <li><a href="/layanan" class="hover:text-brand-400 transition-colors flex items-center gap-2 group"><i class="ph-bold ph-caret-right opacity-0 group-hover:opacity-100 transition-opacity -ml-4 group-hover:ml-0"></i> Cuci Kiloan</a></li>
                        <li><a href="/layanan" class="hover:text-brand-400 transition-colors flex items-center gap-2 group"><i class="ph-bold ph-caret-right opacity-0 group-hover:opacity-100 transition-opacity -ml-4 group-hover:ml-0"></i> Cuci Satuan</a></li>
                        <li><a href="/layanan" class="hover:text-brand-400 transition-colors flex items-center gap-2 group"><i class="ph-bold ph-caret-right opacity-0 group-hover:opacity-100 transition-opacity -ml-4 group-hover:ml-0"></i> Cuci Khusus</a></li>
                    </ul>
                </div>

                <div class="lg:col-span-2 space-y-4 md:space-y-6">
                    <h4 class="font-bold text-lg">Perusahaan</h4>
                    <ul class="space-y-3 md:space-y-4 text-sm text-slate-400">
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

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
       AOS.init({ once: true, mirror: false, duration: 600, easing: 'ease-out-cubic', offset: 50, throttleDelay: 99 });
    </script>

</body>
</html>