<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Happy Laundry - Register</title>

    <!-- favicon -->
    <link rel="shortcut icon" href="{{ asset('linkskuy') }}/assets/images/logo.ico" type="image/x-icon">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- google font link -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="bg-gray-50 text-gray-900 flex items-center justify-center min-h-screen py-10 px-4">
    <div class="w-full max-w-lg bg-white p-8 sm:p-10 rounded-2xl shadow-xl border border-gray-100 m-4">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Buat Akun Baru</h2>
            <p class="text-sm text-gray-500">Daftar ke Happy Laundry untuk memulai</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
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

            <!-- Nama Lengkap Field -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                    <ion-icon name="person-outline" class="text-lg"></ion-icon>
                    Nama Lengkap
                </label>
                <input type="text" id="name" name="name"
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#dc653d] focus:border-[#dc653d] outline-none transition-all @error('name') border-red-500 @enderror"
                    placeholder="Masukkan nama lengkap Anda" value="{{ old('name') }}" required>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Username Field -->
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                    <ion-icon name="at-outline" class="text-lg"></ion-icon>
                    Username
                </label>
                <input type="text" id="username" name="username"
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#dc653d] focus:border-[#dc653d] outline-none transition-all @error('username') border-red-500 @enderror" placeholder="Buat username unik"
                    value="{{ old('username') }}" required>
                <p class="text-gray-500 text-xs mt-1">Digunakan untuk login dan URL profil Anda</p>
                @error('username')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- WhatsApp Number Field -->
            <div>
                <label for="no_wa" class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                    <ion-icon name="logo-whatsapp" class="text-lg"></ion-icon>
                    Nomor WhatsApp
                </label>
                <input type="tel" id="no_wa" name="no_wa"
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#dc653d] focus:border-[#dc653d] outline-none transition-all @error('no_wa') border-red-500 @enderror" placeholder="Contoh: 08123456789"
                    value="{{ old('no_wa') }}" required>
                <p class="text-gray-500 text-xs mt-1">Nomor WhatsApp untuk verifikasi</p>
                @error('no_wa')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email Field -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                    <ion-icon name="mail-outline" class="text-lg"></ion-icon>
                    Email
                </label>
                <input type="email" id="email" name="email"
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#dc653d] focus:border-[#dc653d] outline-none transition-all @error('email') border-red-500 @enderror" placeholder="Masukkan alamat email"
                    value="{{ old('email') }}" required>
                @error('email')
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
                        placeholder="Buat password yang kuat" required>
                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700 password-toggle" data-password-toggle
                        aria-label="Toggle password visibility">
                        <ion-icon name="eye-outline"></ion-icon>
                    </button>
                </div>
                <p class="text-gray-500 text-xs mt-1">Minimal 6 karakter</p>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password Field -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                    <ion-icon name="shield-checkmark-outline" class="text-lg"></ion-icon>
                    Konfirmasi Password
                </label>
                <div class="relative password-input-group">
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#dc653d] focus:border-[#dc653d] outline-none transition-all pr-10 @error('password_confirmation') border-red-500 @enderror"
                        placeholder="Ulangi password Anda" required>
                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700 password-toggle" data-password-toggle
                        aria-label="Toggle password visibility">
                        <ion-icon name="eye-outline"></ion-icon>
                    </button>
                </div>
                @error('password_confirmation')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Terms & Conditions -->
            <div class="flex items-start gap-2 pt-2">
                <div class="flex items-center h-5">
                    <input type="checkbox" id="agree-terms" name="agree-terms" required class="w-4 h-4 text-[#dc653d] border-gray-300 rounded focus:ring-[#dc653d] cursor-pointer">
                </div>
                <label for="agree-terms" class="text-sm text-gray-600 cursor-pointer">
                    Saya setuju dengan <a href="#" class="text-[#dc653d] hover:underline">Syarat & Ketentuan</a> dan <a href="#" class="text-[#dc653d] hover:underline">Kebijakan Privasi</a>
                </label>
                @error('agree-terms')
                    <p class="text-red-500 text-xs mt-1 block w-full">{{ $message }}</p>
                @enderror
            </div>

            <!-- Register Button -->
            <button type="submit"
                class="w-full py-3 px-4 bg-[#dc653d] hover:bg-[#c4532b] text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all flex justify-center items-center mt-4">
                Daftar Sekarang
            </button>

            <!-- Login Link -->
            <p class="text-center text-sm text-gray-600 mt-6">
                Sudah punya akun? <a href="{{ route('login') }}" class="text-[#dc653d] hover:text-[#c4532b] font-medium hover:underline">Masuk sekarang</a>
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
