<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use RealRashid\SweetAlert\Facades\Alert;

class ManagePelangganController extends Controller
{
    public function index()
    {
        $pelanggan = User::where('role', 'user')->orderBy('created_at', 'desc')->get();
        return view('pagesuperadmin.manage_pelanggan.index', compact('pelanggan'));
    }

    public function destroy($id)
    {
        $pelanggan = User::find($id);
        $pelanggan->delete();
        Alert::success('Success', 'Pelanggan berhasil dihapus');
        return redirect()->route('manage-pelanggan.index');
    }
}