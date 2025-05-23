<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;300;400;500;700&display=swap"
        rel="stylesheet" />

    {{-- AOS CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">

    {{-- Bootwatch --}}
    <link href="https://bootswatch.com/5/zephyr/bootstrap.min.css" rel="stylesheet" />

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>


    <!-- Scripts -->
    {{-- @vite(['resources/js/app.js']) --}}

    <style type="text/css">
        body {
            font-family: "Montserrat", sans-serif;
        }

        .navbar-custom {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .navbar-custom .navbar-brand {
            color: #0d6efd;
            /* Warna primary Bootstrap */
            font-weight: bold;
        }

        .search-bar {
            /* max-width: 600px; */
            margin: 0 auto;
        }

        .carousel-item {
            height: 500px;
            /* Tinggi carousel */
            background-size: cover;
            background-position: center;
        }

        .carousel-item h1 {
            font-size: 3rem;
            font-weight: bold;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .carousel-item p {
            font-size: 1.5rem;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .product-image {
            position: relative;
            overflow: hidden;
            /* Pastikan gambar tidak keluar dari container */
        }

        .discount-tag {
            position: absolute;
            top: 10px;
            /* Atur posisi vertikal tag */
            left: 10px;
            /* Atur posisi horizontal tag */
            background-color: #f00;
            /* Warna latar belakang tag */
            color: #fff;
            /* Warna teks tag */
            padding: 5px 10px;
            /* Padding tag */
            font-size: 14px;
            /* Ukuran font tag */
            border-radius: 5px;
            /* Bentuk tag (opsional) */
            z-index: 1;
            /* Pastikan tag di atas gambar */
        }


        .favorite-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 10;
        }

        .new-tag {
            position: absolute;
            top: 10px;
            /* Atur posisi vertikal tag */
            left: 10px;
            /* Atur posisi horizontal tag */
            background-color: rgb(13, 148, 46);
            /* Warna latar belakang tag */
            color: #fff;
            /* Warna teks tag */
            padding: 5px 10px;
            /* Padding tag */
            font-size: 14px;
            /* Ukuran font tag */
            border-radius: 5px;
            /* Bentuk tag (opsional) */
            z-index: 1;
            /* Pastikan tag di atas gambar */
        }

        .product-title {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            /* Maksimal 2 baris */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            min-height: 2.8em;
            /* Pastikan tinggi tetap */
        }

        @media (max-width: 768px) {
            .search-bar {
                width: 100%;
                margin: 10px 0;
            }

            .navbar-custom .navbar-brand {
                margin-right: auto;
            }

            .navbar-custom .d-flex {
                width: 100%;
                justify-content: space-between;
                margin-top: 10px;
            }

            .carousel-item {
                height: 300px;
                /* Tinggi carousel untuk mobile */
            }

            .carousel-item h1 {
                font-size: 2rem;
            }

            .carousel-item p {
                font-size: 1rem;
            }
        }
    </style>

    @yield('styles')
</head>

<body>
    <div id="app">

        {{-- Header --}}
        <div class="bg-light py-2 d-none d-md-block">
            <div class="container d-flex justify-content-between">
                <div>
                    <a href="{{ route('landing') }}" class="text-muted me-3">Beranda</a>
                    <a href="{{ route('products.all') }}" class="text-muted me-3">Produk</a>
                    <a href="{{ route('contact') }}" class="text-muted me-3">Kontak</a>
                    <a href="{{ route('chat') }}" class="text-muted">Layanan Pelanggan</a>
                </div>
                <div>
                    <a href="#" class="text-muted me-3"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-muted me-3"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="text-muted"><i class="bi bi-instagram"></i></a>
                </div>
            </div>
        </div>

        {{-- Navbar --}}
        <nav class="navbar navbar-expand-lg navbar-custom">
            <div class="container d-flex justify-content-center">
                <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                    <img src="{{ asset('images/jati-bahagya-logo.png') }}" alt="Logo" height="40">
                    {{-- <span class="ms-2">{{ config('app.name', 'Laravel') }}</span> --}}
                </a>

                <!-- Tombol Toggler untuk Mobile -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                    aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Search Bar dan Tombol Masuk & Daftar -->
                <div class="collapse navbar-collapse" id="navbarContent">
                    <!-- Search Bar di Tengah -->
                    <div class="search-bar d-flex justify-content-between">
                        <form class="d-flex mx-2" action="{{ route('products.all') }}" method="GET">
                            <input class="form-control me-2" type="search" name="search" placeholder="Cari..."
                                aria-label="Search" value="{{ request('search') }}">
                            <button class="btn btn-outline-primary" type="submit">
                                Cari
                            </button>
                        </form>
                        <div class="d-flex align-items-center mx-2">
                            <a href="{{ route('address.index') }}" class="nav-link d-flex align-items-center">
                                <i class="bi bi-geo-alt-fill text-primary me-2"></i>
                                <span class="text-muted">Dikirim ke <span class="fw-bold">Alamat Kamu</span></span>
                            </a>
                        </div>
                    </div>

                    <!-- Tombol Masuk & Daftar di Kanan -->
                    <div class="d-flex justify-items-center align-items-center">
                        <a href="{{ route('cart.index') }}" class="btn position-relative me-3">
                            <i class="bi bi-cart"></i>
                            @php
                                $cartCount = \App\Models\Cart::where('user_id', Auth::id())->count();
                            @endphp
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $cartCount }}
                            </span>
                        </a>
                        @guest
                            @if (Route::has('login'))
                                <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Masuk</a>
                            @endif

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                            @endif
                        @else
                            <a class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('orders.index') }}">Pesanan Saya</a>
                                    <a class="dropdown-item" href="{{ route('profile.show') }}">Profil</a>
                                    <a class="dropdown-item" href="{{ route('address.index') }}">Alamat</a>
                                    <a class="dropdown-item" href="{{ route('profile.password.change') }}">Kata Sandi</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Keluar
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </a>
                        @endguest
                    </div>
                </div>

            </div>
        </nav>

        {{-- Content --}}
        <main class="py-0 d-flex flex-column min-vh-100">
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="bg-light py-4">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
                        <h5 class="mt-1">JatiBahagya</h5>
                        <ul class="list-unstyled">
                            <li><a href="#" class="text-muted text-decoration-none">Tentang JatiBahagya</a></li>
                            <li><a href="#" class="text-muted text-decoration-none">Hak Kekayaan Intelektual</a>
                            </li>
                            <li><a href="#" class="text-muted text-decoration-none">Promo Bulanan</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
                        <h5 class="mt-1">Jam Operasional</h5>
                        <p class="text-muted">Senin - Minggu: 9:00 - 18:00 WIB</p>
                    </div>
                    <div class="col-lg-3 col-md-6 text-lg-center">
                        <h5 class="mt-1">Ikuti Kami</h5>
                        <ul class="list-inline">
                            <li class="list-inline-item"><a href="#" class="text-muted"><i
                                        class="bi bi-facebook"></i></a></li>
                            <li class="list-inline-item"><a href="#" class="text-muted"><i
                                        class="bi bi-twitter"></i></a></li>
                            <li class="list-inline-item"><a href="#" class="text-muted"><i
                                        class="bi bi-instagram"></i></a></li>
                            <li class="list-inline-item"><a href="#" class="text-muted"><i
                                        class="bi bi-youtube"></i></a></li>
                        </ul>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h5 class="mt-1">Cek Aplikasi</h5>
                        <ul class="list-unstyled">
                            <li><span class="text-success"><i class="bi bi-cash-coin"></i></span> Diskon 70%* hanya di
                                aplikasi</li>
                            <li><span class="text-info"><i class="bi bi-gift"></i></span> Promo khusus aplikasi</li>
                        </ul>
                    </div>
                </div>
                <hr class="my-4">
                <div class="row">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <p class="text-muted">Buka aplikasi dengan scan QR atau klik tombol:</p>
                        <div class="d-flex">
                            <img src="{{ asset('images/qr-app.png') }}" alt="QR Code" class="img-fluid me-3"
                                width="120">
                            <div>
                                <a href="#" class="btn btn-sm btn-outline-secondary mb-2">
                                    <div style="width: 110px;">
                                        <img src="https://play.google.com/intl/en_us/badges/static/images/badges/en_badge_web_generic.png"
                                            alt="Google Play" width="100%">
                                    </div>
                                </a><br>
                                <a href="#" class="btn btn-sm btn-outline-secondary mb-2">
                                    <div style="width: 110px;">
                                        <img src="https://developer.apple.com/app-store/marketing/guidelines/images/badge-download-on-the-app-store.svg"
                                            alt="App Store" width="100%">
                                    </div>
                                </a><br>
                            </div>
                        </div>
                        <a href="#" class="text-muted text-decoration-none mt-4">Pelajari Selengkapnya</a>
                    </div>
                    <div class="col-md-6 text-end">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12 text-center">
                        <p class="text-muted">&copy; 2025 JatiBahagya. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Jquery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS (Pastikan ini ada sebelum script custom-mu) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- AOS JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>

    {{-- AOS Init --}}
    <script type="text/javascript">
        AOS.init();
    </script>

    @yield('scripts')
</body>

</html>
