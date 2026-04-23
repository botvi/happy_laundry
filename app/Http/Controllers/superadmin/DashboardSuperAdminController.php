<?php

namespace App\Http\Controllers\superadmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pesanan;

class DashboardSuperAdminController extends Controller
{
   public function index()
   {
      $totalPelanggan   = User::where('role', 'user')->count();
      $totalPesanan     = Pesanan::count();
      $pesananMenunggu  = Pesanan::where('status_pesanan', 'menunggu_timbangan')->count();
      $pesananProses    = Pesanan::where('status_pesanan', 'diproses')->count();
      $pesananSelesai   = Pesanan::where('status_pesanan', 'selesai')->count();
      $totalPendapatan  = Pesanan::where('status_pesanan', 'selesai')->sum('total_harga');
      $pesananTerbaru   = Pesanan::with(['pelanggan.user', 'paketLaundry'])
                            ->orderBy('created_at', 'desc')
                            ->take(5)->get();

      return view('pagesuperadmin.dashboard.index', compact(
         'totalPelanggan', 'totalPesanan', 'pesananMenunggu',
         'pesananProses', 'pesananSelesai', 'totalPendapatan', 'pesananTerbaru'
      ));
   }
}
