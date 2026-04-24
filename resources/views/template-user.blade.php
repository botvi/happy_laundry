<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Happy Laundry</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #fdfdfd;
            color: #2b2b2b;
        }

        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #f0f0f0;
        }

        .navbar-brand {
            font-weight: 700;
            color: #dc653d !important;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-brand img {
            height: 40px;
        }

        .nav-link {
            font-weight: 500;
            color: #555 !important;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: #dc653d !important;
        }

        .btn-modern {
            background-color: #dc653d;
            color: white;
            border-radius: 6px;
            padding: 10px 24px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: 1px solid #dc653d;
        }

        .btn-modern:hover {
            background-color: #ffffff;
            color: #dc653d;
        }

        .btn-primary {
            background-color: #dc653d !important;
            border-color: #dc653d !important;
        }

        .btn-primary:hover {
            background-color: #c4532b !important;
            border-color: #c4532b !important;
        }

        .btn-outline-primary {
            border-color: #dc653d;
            color: #dc653d;
        }

        .btn-outline-primary:hover {
            background-color: #dc653d;
            color: white;
        }

        .card-modern {
            border: 1px solid #f0f0f0;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
            transition: all 0.3s ease;
            background: white;
        }

        .card-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
            border-color: #fbd6c9;
        }

        .hero-section {
            background-color: #fdfdfd;
            padding: 80px 0;
        }

        .text-primary {
            color: #dc653d !important;
        }

        .bg-primary {
            background-color: #dc653d !important;
        }

        .bg-primary-subtle {
            background-color: #fcece6 !important;
        }
    </style>
    @yield('styles')
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('index') }}">
                <img src="{{ asset('env/logo_nobg.png') }}" alt="Happy Laundry Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.paket') }}">Daftar Paket</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.riwayat') }}">Riwayat Pesanan</a>
                    </li>
                    @auth
                        <li class="nav-item ms-3">
                            <div class="dropdown">
                                <button class="btn btn-outline-primary dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown">
                                    {{ Auth::user()->name }}
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('user.profil') }}">Profil Saya</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item text-danger" href="{{ route('logout') }}">Logout</a></li>
                                </ul>
                            </div>
                        </li>
                    @else
                        <li class="nav-item ms-3">
                            <a class="btn btn-modern" href="{{ route('login') }}">Login</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <footer class="mt-5 py-4 border-top">
        <div class="container text-center">
            <p class="mb-0 text-muted">&copy; {{ date('Y') }} Happy Laundry. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('sweetalert::alert')
    @yield('scripts')
</body>

</html>
