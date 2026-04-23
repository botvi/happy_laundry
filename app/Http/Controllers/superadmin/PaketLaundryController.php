<?php

namespace App\Http\Controllers\superadmin;

use App\Models\PaketLaundry;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\Controller;

class PaketLaundryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pakets = PaketLaundry::all();
        return view('pagesuperadmin.paket_laundry.index', compact('pakets'));
    }

    public function create()
    {
        return view('pagesuperadmin.paket_laundry.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_paket' => 'required|string',
            'deskripsi' => 'required',
            'harga_paket_per_kg' => 'required|numeric'
        ]);

        PaketLaundry::create($request->all());
        Alert::success('Berhasil', 'Paket Laundry berhasil ditambahkan!');
        return redirect()->route('paket-laundry.index');
    }

    public function show(PaketLaundry $paketLaundry)
    {
        //
    }

    public function edit($id)
    {
        $paket = PaketLaundry::findOrFail($id);
        return view('pagesuperadmin.paket_laundry.edit', compact('paket'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_paket' => 'required|string',
            'deskripsi' => 'required',
            'harga_paket_per_kg' => 'required|numeric'
        ]);

        $paket = PaketLaundry::findOrFail($id);
        $paket->update($request->all());
        Alert::success('Berhasil', 'Paket Laundry berhasil diubah!');
        return redirect()->route('paket-laundry.index');
    }

    public function destroy($id)
    {
        $paket = PaketLaundry::findOrFail($id);
        $paket->delete();
        Alert::success('Berhasil', 'Paket Laundry berhasil dihapus!');
        return redirect()->route('paket-laundry.index');
    }
}
