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
    ManageTestimoniController,
    ManagePelangganController,
    ProfilController as ProfilSuperAdminController,
    BrandController,
};
use App\Http\Controllers\user\{
    PreviewController,
    LinkController,
    EditorController,
    IndexController,
    SkuyController,
    ProfilController,
    TestimoniController,
};
use App\Http\Controllers\auth\{
    LoginController,
    RegisterController,
    GoogleController,
    ForgotPasswordController,
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

// Route::get('/run-superadmin', function () {
//     Artisan::call('db:seed', [
//         '--class' => 'SuperAdminSeeder'
//     ]);

//     return "SuperAdminSeeder has been create successfully!";
// });
// Manual
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Google
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
Route::get('/auth/google/complete', [GoogleController::class, 'showCompleteForm'])->name('google.complete');
Route::post('/auth/google/complete-register', [GoogleController::class, 'completeRegister'])->name('google.complete.register');

// Forgot Password
Route::get('/forgot-password', [ForgotPasswordController::class, 'showRequestOtpForm'])->name('forgot-password');
Route::get('/forgot-password/verify', [ForgotPasswordController::class, 'showVerifyOtpForm'])->name('forgot-password.verify');
Route::get('/forgot-password/reset', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('forgot-password.reset');

Route::post('/forgot-password/request-otp', [ForgotPasswordController::class, 'requestOtp'])->name('forgot-password.request-otp');
Route::post('/forgot-password/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('forgot-password.verify-otp');
Route::post('/forgot-password/reset', [ForgotPasswordController::class, 'resetPassword'])->name('forgot-password.reset-password');


Route::group(['middleware' => ['role:superadmin']], function () {
    Route::get('/profil-superadmin', [ProfilSuperAdminController::class, 'index'])->name('profil-superadmin');
    Route::put('/profil-superadmin/update', [ProfilSuperAdminController::class, 'update'])->name('profil-superadmin.update');
    Route::get('/dashboard-superadmin', [DashboardSuperAdminController::class, 'index'])->name('dashboard-superadmin');
    Route::get('whatsapp-api', [ApiWhatsappController::class, 'index'])->name('whatsapp-api.index');
    Route::post('whatsapp-api', [ApiWhatsappController::class, 'storeorupdate'])->name('whatsapp-api.storeorupdate');
    Route::get('manage-testimoni', [ManageTestimoniController::class, 'index'])->name('manage-testimoni.index');
    Route::delete('manage-testimoni/destroy/{id}', [ManageTestimoniController::class, 'destroy'])->name('manage-testimoni.destroy');
    Route::get('manage-pelanggan', [ManagePelangganController::class, 'index'])->name('manage-pelanggan.index');
    Route::delete('manage-pelanggan/destroy/{id}', [ManagePelangganController::class, 'destroy'])->name('manage-pelanggan.destroy');
    
    // Brand Management Routes
    Route::resource('brand', BrandController::class);
    Route::patch('brand/{id}/toggle-status', [BrandController::class, 'toggleStatus'])->name('brand.toggle-status');
});

Route::get('/gg/{nama_link}', [PreviewController::class, 'index'])->name('preview');

// Route untuk user
Route::group(['middleware' => ['auth']], function () {
    Route::get('/editor/{kode_unik}/{nama_link}', [EditorController::class, 'index'])->name('editor');
    
    // Routes dengan parameter kode_unik dan nama_link
    Route::get('/get-layout/{kode_unik}/{nama_link}', [LinkController::class, 'getLayout'])->name('get-layout');
    Route::get('/test-profile/{kode_unik}/{nama_link}', [LinkController::class, 'testProfile'])->name('test-profile');
    Route::post('/store-link/{kode_unik}/{nama_link}', [LinkController::class, 'store'])->name('store-link');
    Route::post('/store-layout/{kode_unik}/{nama_link}', [LinkController::class, 'storeLayout'])->name('store-layout');
    Route::post('/update-profile/{kode_unik}/{nama_link}', [LinkController::class, 'updateProfile'])->name('update-profile');
    Route::post('/update-grid-produk/{kode_unik}/{nama_link}', [LinkController::class, 'updateGridProduk'])->name('update-grid-produk');
    Route::post('/update-tombol-link/{kode_unik}/{nama_link}', [LinkController::class, 'updateTombolLink'])->name('update-tombol-link');
    Route::post('/update-youtube-embed/{kode_unik}/{nama_link}', [LinkController::class, 'updateYoutubeEmbed'])->name('update-youtube-embed');
    Route::post('/update-sosial-media/{kode_unik}/{nama_link}', [LinkController::class, 'updateSosialMedia'])->name('update-sosial-media');
    Route::post('/update-portfolio-project/{kode_unik}/{nama_link}', [LinkController::class, 'updatePortfolioProject'])->name('update-portfolio-project');
    Route::post('/update-gambar-thumbnail/{kode_unik}/{nama_link}', [LinkController::class, 'updateGambarThumbnail'])->name('update-gambar-thumbnail');
    Route::post('/update-spotify-embed/{kode_unik}/{nama_link}', [LinkController::class, 'updateSpotifyEmbed'])->name('update-spotify-embed');
    Route::post('/update-background-custom/{kode_unik}/{nama_link}', [LinkController::class, 'updateBackgroundCustom'])->name('update-background-custom');
    Route::post('/clean-element-data/{kode_unik}/{nama_link}', [LinkController::class, 'cleanElementData'])->name('clean-element-data');
});


Route::get('/', [IndexController::class, 'index'])->name('index');
Route::get('/skuy', [SkuyController::class, 'index'])->name('skuy');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil');
    Route::put('/profil/update', [ProfilController::class, 'update'])->name('profil.update');
    Route::post('/skuy', [SkuyController::class, 'store'])->name('skuy.store');
    Route::get('/skuy/destroy/{id}', [SkuyController::class, 'destroy'])->name('skuy.destroy');
    Route::post('/testimoni', [TestimoniController::class, 'store'])->name('testimoni.store');
});