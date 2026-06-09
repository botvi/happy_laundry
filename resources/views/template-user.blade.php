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
        :root {
            --primary: #ff7555;
            --secondary: #ff9870;
            --dark: #2b3a55;
            --light: #f8f9fa;
            --gradient: linear-gradient(135deg, #ff7555, #ff9870);
            --gradient-hover: linear-gradient(135deg, #ff9870, #ff7555);
            --glass-bg: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(255, 255, 255, 0.4);
            --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 10px 30px rgba(255, 117, 85, 0.15);
            --shadow-lg: 0 20px 40px rgba(0, 0, 0, 0.08);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: #fafbfc;
            color: #4a5568;
            -webkit-font-smoothing: antialiased;
        }

        /* Glassmorphism Navbar */
        .navbar-custom {
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--glass-border);
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }

        .navbar-brand {
            font-weight: 800;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 1.6rem;
            display: flex;
            align-items: center;
            gap: 12px;
            letter-spacing: -0.5px;
        }

        .navbar-brand img {
            height: 44px;
            filter: drop-shadow(0 4px 6px rgba(255, 117, 85, 0.3));
        }

        .nav-link {
            font-weight: 500;
            color: #4a5568 !important;
            transition: all 0.3s ease;
            position: relative;
            padding: 0.5rem 1rem;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 50%;
            background: var(--gradient);
            transition: all 0.3s ease;
            transform: translateX(-50%);
            border-radius: 2px;
        }

        .nav-link:hover {
            color: var(--primary) !important;
        }

        .nav-link:hover::after {
            width: 80%;
        }

        /* Buttons */
        .btn-modern {
            background: var(--gradient);
            color: white;
            border-radius: 50px;
            padding: 10px 28px;
            font-weight: 600;
            letter-spacing: 0.5px;
            border: none;
            box-shadow: 0 4px 15px rgba(255, 117, 85, 0.3);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .btn-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--gradient-hover);
            z-index: -1;
            transition: opacity 0.4s ease;
            opacity: 0;
        }

        .btn-modern:hover {
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 117, 85, 0.4);
        }

        .btn-modern:hover::before {
            opacity: 1;
        }

        .btn-outline-primary {
            border: 2px solid var(--primary);
            color: var(--primary);
            border-radius: 50px;
            font-weight: 600;
            padding: 8px 24px;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background: var(--gradient);
            border-color: transparent;
            color: white;
            box-shadow: 0 4px 15px rgba(255, 117, 85, 0.3);
            transform: translateY(-2px);
        }

        /* Modern Cards */
        .card-modern {
            background: white;
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 20px;
            box-shadow: var(--shadow-sm);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            z-index: 1;
            overflow: hidden;
        }
        
        .card-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0));
            z-index: -1;
            pointer-events: none;
        }

        .card-modern:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
            border-color: rgba(255, 117, 85, 0.2);
        }

        /* Utilities */
        .text-primary {
            color: var(--primary) !important;
        }

        .bg-primary {
            background: var(--gradient) !important;
        }

        .bg-primary-subtle {
            background-color: rgba(255, 117, 85, 0.1) !important;
        }
        
        .gradient-text {
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .icon-box {
            width: 70px;
            height: 70px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 20px;
            background: linear-gradient(135deg, rgba(255, 117, 85, 0.1), rgba(255, 152, 112, 0.1));
            color: var(--primary);
            font-size: 2rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .card-modern:hover .icon-box {
            background: var(--gradient);
            color: white;
            transform: scale(1.1) rotate(5deg);
        }

        .form-control, .form-select {
            border-radius: 12px;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
            background-color: #f8fafc;
        }

        .form-control:focus, .form-select:focus {
            background-color: #ffffff;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(255, 117, 85, 0.1);
        }

        .form-label {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
        
        .badge-custom {
            padding: 0.5em 1em;
            border-radius: 50px;
            font-weight: 500;
            letter-spacing: 0.5px;
        }
        
        iframe {
            width: 100% !important;
            height: 100% !important;
            border: none;
        }
    </style>
    @yield('styles')
</head>

<body>

    @php
        $globalSetting = \App\Models\LandingSetting::first();
    @endphp

    @if($globalSetting && $globalSetting->running_text)
    <div class="bg-primary text-white text-center py-2" style="font-size: 14px; font-weight: 500;">
        <marquee behavior="scroll" direction="left" scrollamount="5">
            {{ $globalSetting->running_text }}
        </marquee>
    </div>
    @endif

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
                    @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.riwayat') }}">Riwayat Pesanan</a>
                    </li>
                    @endauth
                    @auth
                        <li class="nav-item ms-3">
                            <div class="dropdown">
                                <button class="btn btn-outline-primary dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown">
                                    {{ Auth::user()->name }}
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('user.profil') }}">Profil Saya</a></li>
                                    <li><a class="dropdown-item" href="{{ route('user.komplain.index') }}">Bantuan / Komplain</a></li>
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
