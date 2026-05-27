<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pesanan::with(['pelanggan.user', 'paketLaundry']);

        // Filter status
        if ($request->filled('status')) {
            $query->where('status_pesanan', $request->status);
        }

        // Filter periode cepat: minggu / bulan / tahun
        $periode = $request->input('periode');

        if ($periode === 'minggu') {
            $query->whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek(),
            ]);
        } elseif ($periode === 'bulan') {
            $query->whereYear('created_at', Carbon::now()->year)
                  ->whereMonth('created_at', Carbon::now()->month);
        } elseif ($periode === 'tahun') {
            $query->whereYear('created_at', Carbon::now()->year);
        } elseif ($request->filled('dari') && $request->filled('sampai')) {
            // Filter tanggal manual
            $query->whereBetween('created_at', [
                $request->dari . ' 00:00:00',
                $request->sampai . ' 23:59:59',
            ]);
        }

        $pesanans        = $query->orderBy('created_at', 'desc')->get();
        $totalPendapatan = $pesanans->where('status_pesanan', 'selesai')->sum('total_harga');

        return view('pagesuperadmin.laporan.index', compact('pesanans', 'totalPendapatan'));
    }

    public function print(Request $request)
    {
        $query = Pesanan::with(['pelanggan.user', 'paketLaundry']);

        if ($request->filled('status')) {
            $query->where('status_pesanan', $request->status);
        }

        $periode = $request->input('periode');

        if ($periode === 'minggu') {
            $query->whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek(),
            ]);
        } elseif ($periode === 'bulan') {
            $query->whereYear('created_at', Carbon::now()->year)
                  ->whereMonth('created_at', Carbon::now()->month);
        } elseif ($periode === 'tahun') {
            $query->whereYear('created_at', Carbon::now()->year);
        } elseif ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('created_at', [
                $request->dari . ' 00:00:00',
                $request->sampai . ' 23:59:59',
            ]);
        }

        $pesanans        = $query->orderBy('created_at', 'desc')->get();
        $totalPendapatan = $pesanans->where('status_pesanan', 'selesai')->sum('total_harga');

        return view('pagesuperadmin.laporan.print', compact('pesanans', 'totalPendapatan'));
    }
}
