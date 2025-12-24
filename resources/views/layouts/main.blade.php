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
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Outfit"', 'sans-serif'] },
                    fontSize: {
                        'xs': '0.625rem', 'sm': '0.75rem', 'base': '0.875rem', 
                        'lg': '1rem', 'xl': '1.125rem', '2xl': '1.25rem', 
                        '3xl': '1.5rem', '4xl': '1.625rem',    
                    },
                    colors: {
                        brand: { 50: '#eff6ff', 100: '#dbeafe', 500: '#3b82f6', 600: '#2563eb', 900: '#1e3a8a' },
                        fresh: { 400: '#22d3ee', 500: '#06b6d4' }
                    },
                    boxShadow: {
                        'glass': '0 8px 32px 0 rgba(31, 38, 135, 0.07)',
                        'glow': '0 0 20px rgba(59, 130, 246, 0.5)',
                    },
                    animation: { 'float': 'float 6s ease-in-out infinite' },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        }
                    }
                }
            }
        }
    </script>

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
<body class="text-slate-600 antialiased font-sans flex flex-col min-h-screen">

    <nav x-data="{ scrolled: false, mobileOpen: false }" 
         @scroll.window="scrolled = (window.pageYOffset > 20)"
         class="fixed top-0 w-full z-50 transition-all duration-300 px-4 md:px-0"
         :class="scrolled ? 'py-2' : 'py-6'">
        
        <div class="max-w-7xl mx-auto rounded-2xl transition-all duration-300 border border-transparent"
             :class="scrolled ? 'bg-white/80 backdrop-blur-lg shadow-glass border-white/40 px-6 py-3' : 'bg-transparent px-6'">
            
            <div class="flex justify-between items-center">
                
                <a href="/" class="flex items-center gap-3 group">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo Ni Laundry" class="h-10 w-auto object-contain transition-transform duration-300 group-hover:scale-105">
                    
                    <span class="text-2xl font-bold text-slate-800 tracking-tight group-hover:text-brand-600 transition-colors">
                        Ni Laundry<span class="text-fresh-500">.</span>
                    </span>
                </a>

                <div class="hidden md:flex items-center gap-1 bg-white/50 p-1.5 rounded-full border border-white/50 backdrop-blur-sm">
                    
                    {{-- MENU TENGAH: Logika Tampilan --}}
                    @if(Auth::guard('admin')->check())
                        <a href="/admin/dashboard" class="px-4 py-2 rounded-full text-sm font-bold transition-all {{ Request::is('admin/dashboard') ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-500 hover:text-slate-900' }}">Dashboard</a>
                        <a href="/admin/pesanan" class="px-4 py-2 rounded-full text-sm font-bold transition-all {{ Request::is('admin/pesanan*') ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-500 hover:text-slate-900' }}">Pesanan</a>
                        <a href="/admin/layanan" class="px-4 py-2 rounded-full text-sm font-bold transition-all {{ Request::is('admin/layanan*') ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-500 hover:text-slate-900' }}">Layanan</a>
                        <a href="/admin/diskon" class="px-4 py-2 rounded-full text-sm font-bold transition-all {{ Request::is('admin/diskon*') ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-500 hover:text-slate-900' }}">Diskon</a>
                        <a href="/admin/users" class="px-4 py-2 rounded-full text-sm font-bold transition-all {{ Request::is('admin/users*') ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-500 hover:text-slate-900' }}">Admin</a>

                    @else 
                        {{-- Link Layanan & Riwayat HANYA MUNCUL JIKA LOGIN (Agar tamu tidak bingung) --}}
                        @auth
                            <a href="/" class="px-5 py-2 rounded-full text-sm font-semibold transition-all {{ Request::is('dashboard') ? 'bg-white text-brand-600 shadow-sm' : 'text-slate-500 hover:text-brand-600' }}">Beranda</a>
                            <a href="/layanan" class="px-5 py-2 rounded-full text-sm font-semibold transition-all {{ Request::is('layanan') ? 'bg-white text-brand-600 shadow-sm' : 'text-slate-500 hover:text-brand-600' }}">Layanan</a>
                            <a href="/riwayat" class="px-5 py-2 rounded-full text-sm font-semibold transition-all {{ Request::is('riwayat') ? 'bg-white text-brand-600 shadow-sm' : 'text-slate-500 hover:text-brand-600' }}">Riwayat</a>
                        @endauth
                    @endif

                </div>

                <div class="hidden md:flex items-center gap-4">
                    @if(Auth::check() || Auth::guard('admin')->check())
                        <div class="flex items-center gap-3">
                            <div class="text-right hidden lg:block">
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Logged in as</span>
                                <span class="block text-sm font-bold text-slate-800">
                                    {{ Auth::guard('admin')->check() ? Auth::guard('admin')->user()->username : Auth::user()->nama }}
                                </span>
                            </div>
                            <a href="/logout" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center hover:bg-red-50 hover:text-red-500 hover:border-red-200 transition-all shadow-sm" title="Logout">
                                <i class="ph-bold ph-sign-out"></i>
                            </a>
                        </div>
                    @else
                        <a href="/login" class="px-6 py-2.5 rounded-xl bg-slate-900 text-white text-sm font-bold hover:bg-brand-600 hover:shadow-glow hover:-translate-y-0.5 transition-all duration-300">
                            Login
                        </a>
                    @endif
                </div>

                <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 text-slate-800">
                    <i class="ph-bold ph-list text-2xl"></i>
                </button>
            </div>
        </div>

        <div x-show="mobileOpen" x-collapse x-cloak class="md:hidden absolute top-full left-0 w-full px-4 mt-2">
            <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-xl border border-white/50 p-4 flex flex-col gap-2">
                
                @if(Auth::guard('admin')->check())
                    <div class="px-3 py-2 text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 mb-2">Admin Menu</div>
                    <a href="/admin/dashboard" class="p-3 rounded-xl font-bold hover:bg-slate-100">Dashboard</a>
                    <a href="/admin/pesanan" class="p-3 rounded-xl font-bold hover:bg-slate-100">Kelola Pesanan</a>
                    <a href="/admin/layanan" class="p-3 rounded-xl font-bold hover:bg-slate-100">Kelola Layanan</a>
                    <a href="/admin/diskon" class="p-3 rounded-xl font-bold hover:bg-slate-100">Monitoring Diskon</a>
                    <a href="/admin/users" class="p-3 rounded-xl font-bold hover:bg-slate-100">Kelola Admin</a>
                    <a href="/logout" class="p-3 rounded-xl font-bold text-red-500 bg-red-50 mt-2">Logout</a>

                @elseif(Auth::guard('web')->check())
                    <a href="/" class="px-5 py-2 rounded-full text-sm font-semibold transition-all {{ Request::is('/') ? 'bg-white text-brand-600 shadow-sm' : 'text-slate-500 hover:text-brand-600' }}">Beranda</a>
                    
                    <a href="/layanan" class="px-5 py-2 rounded-full text-sm font-semibold transition-all {{ Request::is('layanan') ? 'bg-white text-brand-600 shadow-sm' : 'text-slate-500 hover:text-brand-600' }}">Layanan</a>
                    <a href="/riwayat" class="px-5 py-2 rounded-full text-sm font-semibold transition-all {{ Request::is('riwayat') ? 'bg-white text-brand-600 shadow-sm' : 'text-slate-500 hover:text-brand-600' }}">Riwayat</a>
                    
                    <a href="/logout" class="p-3 rounded-xl font-medium text-red-500 bg-red-50 mt-2 md:hidden">Logout</a>

                @else
                    <a href="/" class="p-3 rounded-xl font-medium hover:bg-brand-50 hover:text-brand-600">Beranda</a>
                    <a href="/login" class="p-3 rounded-xl font-bold bg-slate-900 text-white text-center">Login Member</a>
                @endif
            </div>
        </div>
    </nav>

    <main class="flex-grow pt-32 pb-12 px-4 md:px-8 max-w-7xl mx-auto w-full z-10">
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition 
                 class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center gap-3 text-emerald-700 shadow-sm">
                <i class="ph-fill ph-check-circle text-xl"></i>
                <span class="font-medium text-sm">{{ session('success') }}</span>
            </div>
        @endif
        @yield('content')
    </main>

    <footer class="bg-slate-900 text-white mt-20 pt-20 pb-10 rounded-t-[3rem] relative overflow-hidden">
        
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-brand-500/20 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-fresh-500/10 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-8 relative z-10">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-12 mb-16">
                
                <div class="lg:col-span-4 space-y-6">
                    <a href="/" class="flex items-center gap-2 group w-fit">
                        <img src="{{ asset('img/logo.png') }}" alt="Logo Ni Laundry" class="h-10 w-auto object-contain transition-transform duration-300 group-hover:scale-105">
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

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
      AOS.init({ once: true, mirror: false, duration: 600, easing: 'ease-out-cubic', offset: 50, throttleDelay: 99 });
    </script>

</body>
</html>