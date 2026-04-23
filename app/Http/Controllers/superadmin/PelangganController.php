<?php

namespace App\Http\Controllers\superadmin;

use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\Controller;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggans = User::where('role', 'user')
                        ->with('pelanggan')
                        ->orderBy('created_at', 'desc')
                        ->get();
        return view('pagesuperadmin.pelanggan.index', compact('pelanggans'));
    }

    public function show($id)
    {
        $user = User::with(['pelanggan.pesanan.paketLaundry'])->findOrFail($id);
        return view('pagesuperadmin.pelanggan.detail', compact('user'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        Alert::success('Berhasil', 'Data pelanggan berhasil dihapus!');
        return redirect()->route('pelanggan.index');
    }
}
