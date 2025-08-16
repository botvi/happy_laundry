<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use RealRashid\SweetAlert\Facades\Alert;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        try {
            return Socialite::driver('google')->redirect();
        } catch (\Exception $e) {
            Alert::error('Terjadi kesalahan saat redirect ke Google. Silakan coba lagi.');
            return redirect('/login');
        }
    }

    public function handleGoogleCallback()
    {
        try {
            // Socialite sudah dikonfigurasi dengan Guzzle verify => false di AppServiceProvider
            $googleUser = Socialite::driver('google')->user();
            
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Update google_id jika belum ada
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->getId()]);
                }

                Auth::login($user);

                // Cek apakah username dan no_wa sudah ada
                if (empty($user->username) || empty($user->no_wa)) {
                    // Jika belum ada, redirect ke halaman lengkapi data
                    return redirect()->route('google.complete', ['user_id' => $user->id]);
                }

                // Langsung gaskeun redirect buat user yang udah lengkap datanya
                if ($user->role == 'superadmin') {
                    Alert::success('Login Mantap!', 'Welcome back, Superadmin! Siap-siap ngatur dunia 😎');
                    return redirect()->route('dashboard-superadmin');
                } else {
                    Alert::success('Login Mantap!', 'Halo bro, selamat datang lagi di Linkskuy!');
                    return redirect()->route('index');
                }
            }

            // Jika user belum terdaftar, buat user baru dengan data minimal
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => Hash::make(uniqid()),
                'role' => 'user',
                'google_id' => $googleUser->getId(),
                'foto_profile' => $googleUser->getAvatar(),
            ]);

            Auth::login($user);

            // Redirect langsung ke halaman lengkapi data untuk user baru
            return redirect()->route('google.complete', ['user_id' => $user->id]);

        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            // Handle invalid state exception dengan redirect dan alert
            Alert::error('Sesi OAuth tidak valid', 'Silakan coba login lagi, bro!');
            return redirect('/login');
            
        } catch (\Exception $e) {
            // Handle SSL certificate errors dan error lainnya dengan redirect dan alert
            $errorMessage = $e->getMessage();
            $customMessage = 'Terjadi kesalahan saat login dengan Google, bro!';

            if (strpos($errorMessage, 'SSL certificate problem') !== false || 
                strpos($errorMessage, 'cURL error 60') !== false) {
                $customMessage = 'Masalah SSL Certificate. Terjadi masalah dengan sertifikat SSL. Silakan coba lagi atau hubungi administrator.';
            } elseif (strpos($errorMessage, 'Client error') !== false) {
                $customMessage = 'Error OAuth. Terjadi kesalahan pada OAuth. Pastikan konfigurasi Google OAuth sudah benar.';
            } else {
                $customMessage = 'Terjadi kesalahan saat login dengan Google: ' . $errorMessage;
            }
            
            Alert::error('Error Google OAuth', $customMessage);
            return redirect('/login');
        }
    }

    public function showCompleteForm(Request $request)
    {
        $user_id = $request->query('user_id');
        $user = User::where('id', $user_id)->first();

        if (!$user) {
            Alert::error('Data Google tidak ditemukan.', 'Silakan login dengan Google terlebih dahulu, bro!');
            return redirect('/login');
        }

        return view('auth.complete-google-register', ['user' => $user]);
    }

    public function completeRegister(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'no_wa' => 'required|string|max:20|unique:users,no_wa',
            'agree-terms' => 'required',
            'user_id' => 'required|string|exists:users,id',
        ], [
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah digunakan',
            'no_wa.required' => 'Nomor WhatsApp wajib diisi',
            'no_wa.unique' => 'Nomor WhatsApp sudah terdaftar',
            'agree-terms.required' => 'Anda harus menyetujui syarat dan ketentuan',
        ]);

        $user = User::where('id', $data['user_id'])->first();

        if (!$user) {
            Alert::error('Data Google tidak ditemukan.', 'Silakan login dengan Google terlebih dahulu, bro!');
            return redirect('/login');
        }

        try {
            $user->update([
                'username' => $data['username'],
                'no_wa' => $data['no_wa'],
            ]);

            Auth::login($user);

            Alert::success('Akun berhasil dibuat lewat Google!', 'Selamat datang di Linkskuy!');
            return redirect()->route('index');
            
        } catch (\Exception $e) {
            Alert::error('Gagal menyelesaikan pendaftaran', 'Terjadi kesalahan. Silakan coba lagi, bro!');
            return back()->withInput();
        }
    }
}
