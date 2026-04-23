<?php

namespace App\Http\Controllers\superadmin;

use App\Models\Pesanan;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\Controller;

class PesananController extends Controller
{
    public function index()
    {
        $pesanans = Pesanan::with(['pelanggan.user', 'paketLaundry'])->get();
        return view('pagesuperadmin.pesanan.index', compact('pesanans'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        $pesanan = Pesanan::with(['pelanggan.user', 'paketLaundry'])->findOrFail($id);
        return view('pagesuperadmin.pesanan.detail', compact('pesanan'));
    }

    public function edit($id)
    {
        $pesanan = Pesanan::with(['pelanggan.user', 'paketLaundry'])->findOrFail($id);
        return view('pagesuperadmin.pesanan.edit', compact('pesanan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jumlah_kilogram'       => 'required|numeric|min:0.1',
            'gambar_bukti_timbangan'=> 'nullable|image|max:5120',
            'status_pesanan'        => 'required',
        ]);

        $pesanan = Pesanan::with(['pelanggan.user', 'paketLaundry'])->findOrFail($id);

        // Upload bukti timbangan
        $newGambar = null;
        if ($request->hasFile('gambar_bukti_timbangan')) {
            $file      = $request->file('gambar_bukti_timbangan');
            $filename  = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/bukti'), $filename);
            $pesanan->gambar_bukti_timbangan = $filename;
            $newGambar = $filename;
        }

        $pesanan->jumlah_kilogram = $request->jumlah_kilogram;
        $pesanan->status_pesanan  = $request->status_pesanan;

        // Hitung total harga otomatis
        $hargaPaket = $pesanan->paketLaundry->harga_paket_per_kg ?? 0;
        $hargaTotal = ($hargaPaket * $request->jumlah_kilogram) + ($pesanan->ongkir_antar_jemput ?? 0);
        $pesanan->total_harga = $hargaTotal;
        $pesanan->save();

        // ======== KIRIM NOTIFIKASI WA via Fonnte ========
        $this->kirimNotifikasiWA($pesanan, $newGambar);

        Alert::success('Berhasil', 'Data Pesanan berhasil diupdate! Notifikasi WA telah dikirim.');
        return redirect()->route('pesanan.index');
    }

    public function destroy($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->delete();
        Alert::success('Berhasil', 'Pesanan berhasil dihapus!');
        return redirect()->route('pesanan.index');
    }

    // ─────────────────────────────────────────────────────
    //  Helper: bangun pesan & kirim WA
    // ─────────────────────────────────────────────────────
    private function kirimNotifikasiWA(Pesanan $pesanan, ?string $newGambar): void
    {
        $noWa = $pesanan->pelanggan->user->no_wa ?? null;
        if (!$noWa) return; // User tidak punya nomor WA

        $nama       = $pesanan->pelanggan->user->name;
        $paket      = $pesanan->paketLaundry->nama_paket ?? '-';
        $status     = strtoupper(str_replace('_', ' ', $pesanan->status_pesanan));
        $berat      = $pesanan->jumlah_kilogram;
        $hargaPerKg = number_format($pesanan->paketLaundry->harga_paket_per_kg ?? 0, 0, ',', '.');
        $hargaLaundry = number_format(($pesanan->paketLaundry->harga_paket_per_kg ?? 0) * $berat, 0, ',', '.');
        $ongkir     = $pesanan->ongkir_antar_jemput
                        ? 'Rp ' . number_format($pesanan->ongkir_antar_jemput, 0, ',', '.')
                        : '-';
        $total      = 'Rp ' . number_format($pesanan->total_harga ?? 0, 0, ',', '.');

        $pesan = "🧺 *Happy Laundry*\n";
        $pesan .= "━━━━━━━━━━━━━━━━━━━━\n";
        $pesan .= "Halo, *{$nama}* 👋\n\n";
        $pesan .= "Status pesanan Anda telah diperbarui!\n\n";
        $pesan .= "📋 *Detail Pesanan:*\n";
        $pesan .= "• Paket      : {$paket}\n";
        $pesan .= "• Status     : *{$status}*\n";
        $pesan .= "• Berat      : {$berat} Kg\n";
        $pesan .= "• Harga/Kg   : Rp {$hargaPerKg}\n";
        $pesan .= "• Harga Cuci : Rp {$hargaLaundry}\n";
        $pesan .= "• Ongkir     : {$ongkir}\n";
        $pesan .= "• *Total     : {$total}*\n";
        $pesan .= "━━━━━━━━━━━━━━━━━━━━\n";
        $pesan .= "Terima kasih telah menggunakan Happy Laundry! 😊";

        $fonnte = new FonnteService();

        // Kalau ada gambar bukti baru, kirim sebagai gambar + caption
        if ($newGambar) {
            $imageUrl = url('uploads/bukti/' . $newGambar);
            $fonnte->sendImage($noWa, $pesan, $imageUrl);
        } else {
            $fonnte->sendText($noWa, $pesan);
        }
    }
}
