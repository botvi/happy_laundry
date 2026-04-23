<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaketLaundry;
use App\Models\SettingOngkos;
use App\Models\Pesanan;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class BuatPesananController extends Controller
{
    public function create(Request $request)
    {
        $paket_id = $request->query('paket_id');
        $pakets = PaketLaundry::all();
        $ongkos = SettingOngkos::first();
        
        return view('pageuser.pesanan.buatpesanan', compact('pakets', 'ongkos', 'paket_id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'paket_laundry_id' => 'required|exists:paket_laundries,id',
            'antar_jemput' => 'required|in:ya,tidak',
            'latitude_antar_jemput' => 'nullable|string',
            'longitude_antar_jemput' => 'nullable|string',
            'ongkir_antar_jemput' => 'nullable|numeric',
        ]);

        $user = Auth::user();
        
        // Ensure pelanggan exists for this user
        $pelanggan = Pelanggan::firstOrCreate(
            ['user_id' => $user->id]
        );

        $dijemput = $request->antar_jemput == 'ya' ? 'ya' : 'tidak';
        $diantar = $request->antar_jemput == 'ya' ? 'ya' : 'tidak';

        Pesanan::create([
            'pelanggan_id' => $pelanggan->id,
            'paket_laundry_id' => $request->paket_laundry_id,
            'dijemput' => $dijemput,
            'diantar' => $diantar,
            'latitude_antar_jemput' => $request->latitude_antar_jemput,
            'longitude_antar_jemput' => $request->longitude_antar_jemput,
            'ongkir_antar_jemput' => $request->antar_jemput == 'ya' ? $request->ongkir_antar_jemput : null,
            'jumlah_kilogram' => null,
            'gambar_bukti_timbangan' => null,
            'total_harga' => null,
            'status_pesanan' => 'menunggu_timbangan'
        ]);

        Alert::success('Berhasil', 'Pesanan berhasil dibuat, silakan tunggu admin menimbang laundry Anda.');
        return redirect()->route('user.riwayat');
    }
}
