# Fitur OTP Rate Limit untuk Reset Password

## Deskripsi
Fitur ini menambahkan pembatasan permintaan OTP untuk mencegah pengguna meminta OTP berulang kali dalam waktu singkat. Pengguna harus menunggu 5 menit sebelum bisa meminta OTP baru.

## Fitur yang Ditambahkan

### 1. Validasi Waktu Tunggu
- Pengguna tidak bisa meminta OTP baru jika masih ada OTP aktif yang belum berumur 5 menit
- Pesan error yang informatif menunjukkan berapa menit lagi yang harus ditunggu

### 2. OTP Expiration
- OTP hanya berlaku selama 5 menit
- OTP lama otomatis dihapus setelah 5 menit

### 3. Automatic Cleanup
- Scheduler otomatis membersihkan OTP yang sudah kadaluarsa setiap 5 menit
- Command artisan untuk membersihkan OTP secara manual
- php artisan otp:cleanup
### 4. Middleware Protection
- Middleware `OtpRateLimit` untuk memvalidasi permintaan OTP
- Diterapkan pada route `forgot-password/request-otp`

## File yang Dimodifikasi

### 1. Controller
- `app/Http/Controllers/auth/ForgotPasswordController.php`
  - Method `requestOtp()`: Ditambahkan validasi waktu tunggu
  - Method `cleanupExpiredOtps()`: Membersihkan OTP kadaluarsa

### 2. Middleware
- `app/Http/Middleware/OtpRateLimit.php` (Baru)
  - Validasi rate limit pada level request

### 3. Console Commands
- `app/Console/Commands/CleanupExpiredOtps.php` (Baru)
  - Command `php artisan otp:cleanup`

### 4. Scheduler
- `app/Console/Kernel.php`
  - Otomatis membersihkan OTP setiap 5 menit

### 5. Routes
- `routes/web.php`
  - Route request OTP ditambahkan middleware `otp.ratelimit`

## Cara Penggunaan

### 1. Scheduler Otomatis
Scheduler akan otomatis berjalan jika cron job Laravel sudah disetup:
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### 2. Command Manual
Untuk membersihkan OTP secara manual:
```bash
php artisan otp:cleanup
```

### 3. Testing
Untuk testing fitur ini:
1. Request OTP pertama kali
2. Coba request OTP lagi dalam waktu 5 menit
3. Sistem akan menampilkan pesan error dengan sisa waktu tunggu

## Keamanan

### 1. Rate Limiting
- Mencegah spam request OTP
- Mencegah abuse sistem

### 2. OTP Expiration
- OTP tidak bisa digunakan setelah 5 menit
- Meningkatkan keamanan reset password

### 3. Database Cleanup
- OTP lama otomatis dihapus
- Mencegah penumpukan data tidak terpakai

## Konfigurasi

### 1. Waktu Tunggu
Untuk mengubah waktu tunggu, edit file:
- `app/Http/Controllers/auth/ForgotPasswordController.php`
- `app/Http/Middleware/OtpRateLimit.php`
- `app/Console/Kernel.php`

Ganti semua `subMinutes(5)` dengan nilai yang diinginkan.

### 2. Scheduler Interval
Untuk mengubah interval scheduler, edit file:
- `app/Console/Kernel.php`
- Ganti `everyFiveMinutes()` dengan interval yang diinginkan

## Troubleshooting

### 1. Scheduler Tidak Berjalan
Pastikan cron job Laravel sudah disetup:
```bash
crontab -e
# Tambahkan:
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### 2. Middleware Error
Pastikan middleware sudah didaftarkan di:
- `app/Http/Kernel.php`
- Route sudah menggunakan middleware yang benar

### 3. Database Error
Pastikan tabel `password_resets` memiliki kolom:
- `no_wa`
- `token`
- `created_at`

## Dependencies
- Laravel Framework
- Carbon (untuk manipulasi waktu)
- SweetAlert (untuk notifikasi)
- Database MySQL/PostgreSQL
