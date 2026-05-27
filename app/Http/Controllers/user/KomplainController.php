<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Komplain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class KomplainController extends Controller
{
    public function index()
    {
        $pelanggan = Auth::user()->pelanggan;
        if (!$pelanggan) {
            Alert::error('Gagal', 'Anda harus melengkapi profil terlebih dahulu.');
            return redirect()->route('user.profil');
        }

        $komplains = Komplain::where('pelanggan_id', $pelanggan->id)->latest()->get();
        return view('pageuser.komplain.index', compact('komplains'));
    }

    public function create(Request $request)
    {
        $pesanan_id = $request->query('pesanan_id');
        return view('pageuser.komplain.create', compact('pesanan_id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subjek' => 'required|string|max:255',
            'pesan' => 'required|string',
            'pesanan_id' => 'nullable|exists:pesanans,id'
        ]);

        $pelanggan = Auth::user()->pelanggan;

        Komplain::create([
            'pelanggan_id' => $pelanggan->id,
            'pesanan_id' => $request->pesanan_id,
            'subjek' => $request->subjek,
            'pesan' => $request->pesan,
            'status' => 'menunggu'
        ]);

        Alert::success('Berhasil', 'Komplain Anda berhasil dikirim dan akan segera ditinjau oleh Admin.');
        return redirect()->route('user.komplain.index');
    }

    public function show($id)
    {
        $pelanggan = Auth::user()->pelanggan;
        $komplain = Komplain::where('pelanggan_id', $pelanggan->id)->findOrFail($id);
        
        return view('pageuser.komplain.show', compact('komplain'));
    }
}
