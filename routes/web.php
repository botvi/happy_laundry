<?php

use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\{
    DashboardController,
};

use App\Http\Controllers\superadmin\{
    DashboardSuperAdminController,
    ApiWhatsappController,
    ProfilController as ProfilSuperAdminController,
    PaketLaundryController,
    SettingOngkosController,
    PesananController,
    PelangganController,
    LaporanController,
};
use App\Http\Controllers\user\{

    IndexController,

};
use App\Http\Controllers\auth\{
    LoginController,
    RegisterController,

};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/run-superadmin', function () {
    Artisan::call('db:seed', [
        '--class' => 'SuperAdminSeeder'
    ]);

    return "SuperAdminSeeder has been create successfully!";
});

// Manual
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);



Route::group(['middleware' => ['role:superadmin']], function () {
    Route::get('/profil-superadmin', [ProfilSuperAdminController::class, 'index'])->name('profil-superadmin');
    Route::put('/profil-superadmin/update', [ProfilSuperAdminController::class, 'update'])->name('profil-superadmin.update');
    Route::get('/dashboard-superadmin', [DashboardSuperAdminController::class, 'index'])->name('dashboard-superadmin');
    Route::get('whatsapp-api', [ApiWhatsappController::class, 'index'])->name('whatsapp-api.index');
    Route::post('whatsapp-api', [ApiWhatsappController::class, 'storeorupdate'])->name('whatsapp-api.storeorupdate');
    
    Route::resource('paket-laundry', PaketLaundryController::class);
    Route::resource('setting-ongkos', SettingOngkosController::class);
    Route::resource('pesanan', PesananController::class);

    Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
    Route::get('/pelanggan/{id}', [PelangganController::class, 'show'])->name('pelanggan.show');
    Route::delete('/pelanggan/{id}', [PelangganController::class, 'destroy'])->name('pelanggan.destroy');

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/print', [LaporanController::class, 'print'])->name('laporan.print');
});




Route::get('/', [IndexController::class, 'index'])->name('index');

Route::group(['middleware' => ['role:user']], function () {
    Route::get('/daftar-paket', [App\Http\Controllers\user\DaftarPaketController::class, 'index'])->name('user.paket');
    Route::get('/buat-pesanan', [App\Http\Controllers\user\BuatPesananController::class, 'create'])->name('user.pesanan.create');
    Route::post('/buat-pesanan', [App\Http\Controllers\user\BuatPesananController::class, 'store'])->name('user.pesanan.store');
    Route::get('/riwayat-pesanan', [App\Http\Controllers\user\RiwayatPesananController::class, 'index'])->name('user.riwayat');
    
    Route::get('/profil', [App\Http\Controllers\user\ProfilUserController::class, 'index'])->name('user.profil');
    Route::post('/profil', [App\Http\Controllers\user\ProfilUserController::class, 'update'])->name('user.profil.update');
});
