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
        $pesanan = Pesanan::with(['pelanggan.user', 'paketLaundry'])->findOrFail($id);
        $isHelai = ($pesanan->paketLaundry->satuan ?? 'kg') === 'helai';

        $request->validate([
            'jumlah_kilogram' => $isHelai ? 'required|integer|min:1' : 'required|numeric|min:0.1',
            'gambar_bukti_timbangan' => 'nullable|image|max:5120',
            'status_pesanan' => 'required',
            'catatan_pelanggan' => 'nullable|string',
        ]);

        // Upload bukti timbangan
        $newGambar = null;
        if ($request->hasFile('gambar_bukti_timbangan')) {
            $file = $request->file('gambar_bukti_timbangan');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/bukti'), $filename);
            $pesanan->gambar_bukti_timbangan = $filename;
            $newGambar = $filename;
        }

        $pesanan->jumlah_kilogram = $request->jumlah_kilogram;
        $pesanan->status_pesanan = $request->status_pesanan;
        $pesanan->catatan_pelanggan = $request->catatan_pelanggan;

        // Hitung total harga otomatis
        $hargaPaket = $pesanan->paketLaundry->harga_paket_per_kg ?? 0;
        $hargaLaundry = ($hargaPaket * $request->jumlah_kilogram);

        // Cek diskon berdasarkan berat/jumlah dan satuan paket
        $satuanPaket = $pesanan->paketLaundry->satuan ?? 'kg';
        $diskon = \App\Models\Diskon::where('satuan', $satuanPaket)
            ->where('minimal_berat_kg', '<=', $request->jumlah_kilogram)
            ->orderBy('minimal_berat_kg', 'desc')
            ->first();

        $potongan = 0;
        if ($diskon) {
            if ($diskon->tipe_diskon == 'persen') {
                $potongan = $hargaLaundry * ($diskon->nilai_diskon / 100);
            } else {
                $potongan = $diskon->nilai_diskon;
            }
        }

        $pesanan->potongan_harga = $potongan;
        $hargaTotal = $hargaLaundry - $potongan + ($pesanan->ongkir_antar_jemput ?? 0);
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

    public function createOffline()
    {
        $pakets = \App\Models\PaketLaundry::all();
        return view('pagesuperadmin.pesanan.create_offline', compact('pakets'));
    }

    public function storeOffline(Request $request)
    {
        $request->validate([
            'paket_laundry_id' => 'required|exists:paket_laundries,id',
            'status_pesanan' => 'required',
            'catatan_pelanggan' => 'nullable|string',
        ]);

        $paket = \App\Models\PaketLaundry::findOrFail($request->paket_laundry_id);
        $isHelai = ($paket->satuan ?? 'kg') === 'helai';

        $request->validate([
            'jumlah_kilogram' => $isHelai ? 'required|integer|min:1' : 'required|numeric|min:0.1',
            'gambar_bukti_timbangan' => $isHelai ? 'nullable' : 'nullable|image|max:5120',
        ]);

        // Find or create pelanggan record for superadmin
        $superadmin = \App\Models\User::where('role', 'superadmin')->first();
        $pelanggan = \App\Models\Pelanggan::firstOrCreate(['user_id' => $superadmin->id]);

        // Upload bukti timbangan
        $gambarTimbangan = null;
        if (!$isHelai && $request->hasFile('gambar_bukti_timbangan')) {
            $file = $request->file('gambar_bukti_timbangan');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/bukti'), $filename);
            $gambarTimbangan = $filename;
        }

        // Calculate laundry price
        $hargaPaket = $paket->harga_paket_per_kg ?? 0;
        $hargaLaundry = ($hargaPaket * $request->jumlah_kilogram);

        // Check discount
        $diskon = \App\Models\Diskon::where('satuan', $paket->satuan ?? 'kg')
            ->where('minimal_berat_kg', '<=', $request->jumlah_kilogram)
            ->orderBy('minimal_berat_kg', 'desc')
            ->first();

        $potongan = 0;
        if ($diskon) {
            if ($diskon->tipe_diskon == 'persen') {
                $potongan = $hargaLaundry * ($diskon->nilai_diskon / 100);
            } else {
                $potongan = $diskon->nilai_diskon;
            }
        }

        $totalHarga = $hargaLaundry - $potongan;

        Pesanan::create([
            'pelanggan_id' => $pelanggan->id,
            'paket_laundry_id' => $paket->id,
            'dijemput' => 'tidak',
            'diantar' => 'tidak',
            'ongkir_antar_jemput' => null,
            'jumlah_kilogram' => $request->jumlah_kilogram,
            'gambar_bukti_timbangan' => $gambarTimbangan,
            'total_harga' => $totalHarga,
            'potongan_harga' => $potongan,
            'status_pesanan' => $request->status_pesanan,
            'catatan_pelanggan' => $request->catatan_pelanggan,
        ]);

        Alert::success('Berhasil', 'Pesanan Offline berhasil dibuat!');
        return redirect()->route('pesanan.index');
    }

    // ─────────────────────────────────────────────────────
    //  Helper: bangun pesan & kirim WA
    // ─────────────────────────────────────────────────────
    private function kirimNotifikasiWA(Pesanan $pesanan, ?string $newGambar): void
    {
        $noWa = $pesanan->pelanggan->user->no_wa ?? null;
        $isOffline = ($pesanan->pelanggan->user->role ?? '') === 'superadmin';
        if (!$noWa || $isOffline)
            return; // User tidak punya nomor WA atau pesanan offline

        $nama = $pesanan->pelanggan->user->name;
        $paket = $pesanan->paketLaundry->nama_paket ?? '-';
        $status = strtoupper(str_replace('_', ' ', $pesanan->status_pesanan));
        $berat = $pesanan->jumlah_kilogram;
        $labelSatuan = ($pesanan->paketLaundry->satuan ?? 'kg') == 'helai' ? 'Jumlah' : 'Berat';
        $satuan = ($pesanan->paketLaundry->satuan ?? 'kg') == 'helai' ? 'Helai' : 'Kg';
        $labelHargaPerSatuan = ($pesanan->paketLaundry->satuan ?? 'kg') == 'helai' ? 'Harga/Helai' : 'Harga/Kg';
        
        $hargaPerKg = number_format($pesanan->paketLaundry->harga_paket_per_kg ?? 0, 0, ',', '.');
        $hargaLaundry = number_format(($pesanan->paketLaundry->harga_paket_per_kg ?? 0) * $berat, 0, ',', '.');
        $ongkir = $pesanan->ongkir_antar_jemput
            ? 'Rp ' . number_format($pesanan->ongkir_antar_jemput, 0, ',', '.')
            : '-';
        $potongan = $pesanan->potongan_harga
            ? '- Rp ' . number_format($pesanan->potongan_harga, 0, ',', '.')
            : '-';
        $total = 'Rp ' . number_format($pesanan->total_harga ?? 0, 0, ',', '.');

        $pesan = "🧺 *Happy Laundry*\n";
        $pesan .= "━━━━━━━━━━━━━━━━━━━━\n";
        $pesan .= "Halo, *{$nama}* 👋\n\n";
        $pesan .= "Status pesanan Anda telah diperbarui!\n\n";
        $pesan .= "📋 *Detail Pesanan:*\n";
        $pesan .= "• Paket      : {$paket}\n";
        $pesan .= "• Status     : *{$status}*\n";
        $pesan .= "• {$labelSatuan}      : {$berat} {$satuan}\n";
        $pesan .= "• {$labelHargaPerSatuan}   : Rp {$hargaPerKg}\n";
        $pesan .= "• Harga      : Rp {$hargaLaundry}\n";
        $pesan .= "• Ongkir     : {$ongkir}\n";
        $pesan .= "• Diskon     : {$potongan}\n";
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
