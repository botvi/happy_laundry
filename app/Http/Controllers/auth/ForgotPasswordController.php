<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\WhatsappApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;

class ForgotPasswordController extends Controller
{
    public function showRequestOtpForm()
    {
        return view('auth.resetpassword.request-otp');
    }

    public function showVerifyOtpForm()
    {
        return view('auth.resetpassword.verify-otp');
    }

    public function showResetPasswordForm(Request $request)
    {
        $no_wa = $request->no_wa ?? session('no_wa');
        
        if (!$no_wa) {
            Alert::error('Error', 'Nomor WhatsApp tidak ditemukan');
            return redirect()->route('forgot-password');
        }
        
        return view('auth.resetpassword.reset-password', ['no_wa' => $no_wa]);
    }

    public function requestOtp(Request $request)
    {
        $request->validate([
            'no_wa' => 'required'
        ]);

        // Cek apakah nomor WhatsApp ada di database
        $user = User::where('no_wa', $request->no_wa)->first();
        if (!$user) {
            Alert::error('Error', 'Nomor WhatsApp tidak ditemukan di sistem kami');
            return back()->withInput()->withErrors(['no_wa' => 'Nomor WhatsApp tidak terdaftar']);
        }

        $otp = rand(100000, 999999);

        DB::table('password_resets')->updateOrInsert(
            ['no_wa' => $request->no_wa],
            [
                'token' => $otp,
                'created_at' => Carbon::now()
            ]
        );

        // Kirim OTP ke WhatsApp dengan pesan yang lebih rapi
        $pesan = "🔐 *Kode OTP Reset Password*\n\nKode OTP Anda: *$otp*\n\nKode ini berlaku selama 5 menit. Jangan bagikan kode ini kepada siapapun.\n\nJika Anda tidak meminta reset password, abaikan pesan ini.";
        $this->sendWhatsapp($request->no_wa, $pesan);

        Alert::success('Berhasil', 'OTP telah dikirim ke WhatsApp Anda');
        return redirect()->route('forgot-password.verify')->with('no_wa', $request->no_wa);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'no_wa' => 'required',
            'otp' => 'required'
        ]);

        $reset = DB::table('password_resets')
            ->where('no_wa', $request->no_wa)
            ->where('token', $request->otp)
            ->where('created_at', '>', Carbon::now()->subMinutes(5))
            ->first();

        if (!$reset) {
            Alert::error('Error', 'OTP tidak valid atau kadaluarsa');
            return back()->withErrors(['otp' => 'OTP tidak valid atau kadaluarsa']);
        }

        Alert::success('Berhasil', 'OTP berhasil diverifikasi');
        return redirect()->route('forgot-password.reset')->with('no_wa', $request->no_wa);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'no_wa' => 'required',
            'password' => 'required|confirmed|min:6'
        ]);

        User::where('no_wa', $request->no_wa)->update([
            'password' => Hash::make($request->password)
        ]);

        DB::table('password_resets')->where('no_wa', $request->no_wa)->delete();

        Alert::success('Berhasil', 'Password berhasil direset');
        return redirect()->route('login')->with('success', 'Password berhasil direset');
    }

    private function sendWhatsapp($no_wa, $message)
    {
        $token = WhatsappApi::first()->access_token;
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => [
                'target' => $no_wa,
                'message' => $message
            ],
            CURLOPT_HTTPHEADER => [
                "Authorization: $token"
            ],
        ]);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}
