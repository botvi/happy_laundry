<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pesanan::with(['pelanggan.user', 'paketLaundry']);

        if ($request->filled('status')) {
            $query->where('status_pesanan', $request->status);
        }

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('created_at', [
                $request->dari . ' 00:00:00',
                $request->sampai . ' 23:59:59'
            ]);
        }

        $pesanans       = $query->orderBy('created_at', 'desc')->get();
        $totalPendapatan = $pesanans->where('status_pesanan', 'selesai')->sum('total_harga');

        return view('pagesuperadmin.laporan.index', compact('pesanans', 'totalPendapatan'));
    }

    public function print(Request $request)
    {
        $query = Pesanan::with(['pelanggan.user', 'paketLaundry']);

        if ($request->filled('status')) {
            $query->where('status_pesanan', $request->status);
        }
        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('created_at', [
                $request->dari . ' 00:00:00',
                $request->sampai . ' 23:59:59'
            ]);
        }

        $pesanans        = $query->orderBy('created_at', 'desc')->get();
        $totalPendapatan = $pesanans->where('status_pesanan', 'selesai')->sum('total_harga');

        return view('pagesuperadmin.laporan.print', compact('pesanans', 'totalPendapatan'));
    }
}
