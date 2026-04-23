<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Auth;

class RiwayatPesananController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pelanggan = Pelanggan::where('user_id', $user->id)->first();
        
        if($pelanggan) {
            $pesanans = Pesanan::with('paketLaundry')
                        ->where('pelanggan_id', $pelanggan->id)
                        ->orderBy('created_at', 'desc')
                        ->get();
        } else {
            $pesanans = collect();
        }

        return view('pageuser.pesanan.riwayatpesanan', compact('pesanans'));
    }
}
