<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ni Laundry - @yield('title')</title>
    <link rel="icon" href="{{ asset('img/logo_ni_laundry.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body { 
            background-color: #f8f9fa; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .nav-link {
            font-weight: 500;
        }
        .nav-link:hover {
            color: #0d6efd !important;
        }
        footer {
            background-color: #fff;
            margin-top: 50px;
            padding: 30px 0;
            border-top: 1px solid #e9ecef;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
        <a class="navbar-brand py-0 me-4 margin-4" href="#">
            <img src="{{ asset('img/logo_ni_laundry.png') }}" alt="Logo" style="height: 50px; width: auto;">
        </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    
                   @if(Auth::guard('admin')->check())
                    <li class="nav-item"><a class="nav-link" href="/admin/dashboard">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/layanan">Kelola Layanan</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/pesanan">Kelola Pesanan</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/diskon">Diskon Pelanggan</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/users">Kelola Admin</a></li>
                    
                    @elseif(Auth::guard('web')->check())
                        <li class="nav-item">
                            <a class="nav-link" href="/dashboard">Beranda</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link {{ Request::is('layanan') ? 'active text-primary fw-bold' : '' }}" href="/layanan">Layanan & Pesan</a>
                    </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/riwayat">Pesanan Saya</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#about-us">About Us</a>
                        </li>
                    @endif

                </ul>

                <div class="d-flex">
                    @if(Auth::check() || Auth::guard('admin')->check())
                        <div class="dropdown me-2 d-flex align-items-center">
                            <span class="me-2 text-muted">Halo, 
                                <b>{{ Auth::guard('admin')->check() ? Auth::guard('admin')->user()->username : Auth::user()->nama }}</b>
                            </span>
                        </div>
                        <a href="/logout" class="btn btn-outline-danger btn-sm rounded-pill px-4">
                            Logout <i class="bi bi-box-arrow-right"></i>
                        </a>
                    @else
                        <a href="/login" class="btn btn-primary px-4">Login</a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <div class="py-4">
        @if(session('success'))
            <div class="container">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif
        
        @yield('content')
    </div>

    <footer id="about-us">
        <div class="container text-center">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <h5 class="fw-bold text-primary">About Us - Ni Laundry</h5>
                    <p class="text-muted">
                        Ni Laundry adalah penyedia jasa cuci pakaian profesional berbasis teknologi. 
                        Kami mengutamakan kebersihan, kecepatan, dan kenyamanan pelanggan dengan layanan antar jemput.
                    </p>
                    <div class="mt-3">
                        <small class="text-muted">&copy; 2025 Ni Laundry Project. All Rights Reserved.</small>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>