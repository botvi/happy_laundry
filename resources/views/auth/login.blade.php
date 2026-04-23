<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Happy Laundry - Login</title>

    <!-- favicon -->
    <link rel="shortcut icon" href="{{ asset('linkskuy') }}/assets/images/logo.ico" type="image/x-icon">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- google font link -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-900 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white p-8 sm:p-10 rounded-2xl shadow-xl border border-gray-100 m-4">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Selamat Datang!</h2>
            <p class="text-sm text-gray-500">Masuk ke akun Happy Laundry Anda</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm mb-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Username Field -->
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                    <ion-icon name="person-outline" class="text-lg"></ion-icon>
                    Username atau Email
                </label>
                <input type="text" id="username" name="username"
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#dc653d] focus:border-[#dc653d] outline-none transition-all @error('username') border-red-500 @enderror"
                    placeholder="Masukkan username atau email" value="{{ old('username') }}" required>
                @error('username')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Field -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                    <ion-icon name="lock-closed-outline" class="text-lg"></ion-icon>
                    Password
                </label>
                <div class="relative password-input-group">
                    <input type="password" id="password" name="password"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#dc653d] focus:border-[#dc653d] outline-none transition-all pr-10 @error('password') border-red-500 @enderror"
                        placeholder="Masukkan password" required>
                    <button type="button"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700 password-toggle"
                        data-password-toggle aria-label="Toggle password visibility">
                        <ion-icon name="eye-outline"></ion-icon>
                    </button>
                </div>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>


            <!-- Login Button -->
            <button type="submit"
                class="w-full py-3 px-4 bg-[#dc653d] hover:bg-[#c4532b] text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all flex justify-center items-center mt-2">
                Masuk
            </button>

            <!-- Register Link -->
            <p class="text-center text-sm text-gray-600 mt-6">
                Belum punya akun? <a href="{{ route('register') }}"
                    class="text-[#dc653d] hover:text-[#c4532b] font-medium hover:underline">Daftar sekarang</a>
            </p>
        </form>
    </div>

    <!-- custom js link -->
    <script src="{{ asset('linkskuy') }}/assets/js/auth.js"></script>
    @include('sweetalert::alert')

    <!-- ionicon link -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons/5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>
