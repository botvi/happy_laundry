<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\Komplain;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class KomplainController extends Controller
{
    public function index()
    {
        $komplains = Komplain::with(['pelanggan.user', 'pesanan'])->latest()->get();
        return view('pagesuperadmin.komplain.index', compact('komplains'));
    }

    public function show($id)
    {
        $komplain = Komplain::with(['pelanggan.user', 'pesanan'])->findOrFail($id);
        return view('pagesuperadmin.komplain.detail', compact('komplain'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu,diproses,selesai',
            'balasan' => 'nullable|string'
        ]);

        $komplain = Komplain::findOrFail($id);
        $komplain->status = $request->status;
        if ($request->filled('balasan')) {
            $komplain->balasan = $request->balasan;
        }
        $komplain->save();

        Alert::success('Berhasil', 'Status komplain berhasil diperbarui');
        return redirect()->route('superadmin.komplain.index');
    }
}
