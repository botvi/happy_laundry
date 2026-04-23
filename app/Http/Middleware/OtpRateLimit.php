<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;

class OtpRateLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('forgot-password/request-otp') && $request->isMethod('POST')) {
            $no_wa = $request->input('no_wa');
            
            if ($no_wa) {
                // Cek apakah ada OTP yang masih aktif (belum 5 menit)
                $existingReset = DB::table('password_resets')
                    ->where('no_wa', $no_wa)
                    ->where('created_at', '>', Carbon::now()->subMinutes(5))
                    ->first();

                if ($existingReset) {
                    $remainingTime = Carbon::parse($existingReset->created_at)->addMinutes(5)->diffInSeconds(Carbon::now());
                    $remainingMinutes = ceil($remainingTime / 60);
                    
                    Alert::error('Error', "Anda harus menunggu {$remainingMinutes} menit lagi sebelum bisa meminta OTP baru");
                    return back()->withInput()->withErrors(['no_wa' => "Tunggu {$remainingMinutes} menit lagi untuk meminta OTP baru"]);
                }
            }
        }

        return $next($request);
    }
}
