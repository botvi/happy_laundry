<?php

namespace App\Http\Controllers\superadmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pesanan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardSuperAdminController extends Controller
{
   public function index()
   {
      $totalPelanggan   = User::where('role', 'user')->count();
      $totalPesanan     = Pesanan::count();
      $pesananMenunggu  = Pesanan::where('status_pesanan', 'menunggu_timbangan')->count();
      $pesananProses    = Pesanan::where('status_pesanan', 'diproses')->count();
      $pesananDiantar   = Pesanan::where('status_pesanan', 'diantar')->count();
      $pesananSelesai   = Pesanan::where('status_pesanan', 'selesai')->count();
      $totalPendapatan  = Pesanan::where('status_pesanan', 'selesai')->sum('total_harga');
      $pesananTerbaru   = Pesanan::with(['pelanggan.user', 'paketLaundry'])
                            ->orderBy('created_at', 'desc')
                            ->take(5)->get();

      // ── Chart: Pendapatan 12 bulan terakhir ──────────────────────────
      $chartLabels   = [];
      $chartPendapatan = [];
      $chartPesanan  = [];

      for ($i = 11; $i >= 0; $i--) {
         $bulan = Carbon::now()->subMonths($i);
         $chartLabels[] = $bulan->translatedFormat('M Y');

         $chartPendapatan[] = (float) Pesanan::where('status_pesanan', 'selesai')
            ->whereYear('created_at', $bulan->year)
            ->whereMonth('created_at', $bulan->month)
            ->sum('total_harga');

         $chartPesanan[] = (int) Pesanan::whereYear('created_at', $bulan->year)
            ->whereMonth('created_at', $bulan->month)
            ->count();
      }

      // ── Chart: Status Pesanan (Pie/Donut) ────────────────────────────
      $statusLabels = ['Menunggu Timbangan', 'Diproses', 'Diantar', 'Selesai'];
      $statusData   = [$pesananMenunggu, $pesananProses, $pesananDiantar, $pesananSelesai];

      return view('pagesuperadmin.dashboard.index', compact(
         'totalPelanggan', 'totalPesanan', 'pesananMenunggu',
         'pesananProses', 'pesananDiantar', 'pesananSelesai', 'totalPendapatan', 'pesananTerbaru',
         'chartLabels', 'chartPendapatan', 'chartPesanan',
         'statusLabels', 'statusData'
      ));
   }
}
