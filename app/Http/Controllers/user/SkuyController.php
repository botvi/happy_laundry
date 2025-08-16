<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Link;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;


class SkuyController extends Controller
{
    public function index()
    {
        // Jika user sudah login, ambil data link berdasarkan user_id
        if (auth()->check()) {
            $links = Link::where('user_id', auth()->user()->id)->get();
        } else {
            // Jika belum login, tampilkan halaman tanpa data link (atau bisa juga tampilkan semua link publik jika ada)
            $links = collect(); // koleksi kosong
        }

        // Kirim data link ke view
        return view('pageuser.landing.skuy', compact('links'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_link' => 'required|string|max:255',
        ]); 

        // Cek apakah username sudah digunakan
        if (Link::where('nama_link', $request->nama_link)->exists()) {
            Alert::error('Gagal', 'Yahh udah dipake orang bro, coba yang lain');
            return redirect()->back()->withInput();
        }

        $link = Link::create([
            'user_id' => auth()->user()->id,
            'nama_link' => $request->nama_link,
            'kode_unik' => random_int(100000, 999999),
        ]);

        Alert::success('Sukses', 'Link berhasil dibuat');
        return redirect()->route('editor', ['kode_unik' => $link->kode_unik, 'nama_link' => $link->nama_link]);
    }

    public function destroy($id)
    {
        $link = Link::find($id);
        $link->delete();

        Alert::success('Sukses', 'Link berhasil dihapus');
        return redirect()->route('skuy');
    }
}