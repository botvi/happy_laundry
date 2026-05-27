<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\Diskon;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class DiskonController extends Controller
{
    public function index()
    {
        $diskons = Diskon::orderBy('minimal_berat_kg', 'asc')->get();
        return view('pagesuperadmin.diskon.index', compact('diskons'));
    }

    public function create()
    {
        return view('pagesuperadmin.diskon.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'minimal_berat_kg' => 'required|numeric|min:0.1',
            'tipe_diskon'      => 'required|in:persen,nominal',
            'nilai_diskon'     => 'required|numeric|min:1',
        ]);

        Diskon::create($request->all());

        Alert::success('Berhasil', 'Aturan Diskon berhasil ditambahkan');
        return redirect()->route('diskon.index');
    }

    public function edit(Diskon $diskon)
    {
        return view('pagesuperadmin.diskon.edit', compact('diskon'));
    }

    public function update(Request $request, Diskon $diskon)
    {
        $request->validate([
            'minimal_berat_kg' => 'required|numeric|min:0.1',
            'tipe_diskon'      => 'required|in:persen,nominal',
            'nilai_diskon'     => 'required|numeric|min:1',
        ]);

        $diskon->update($request->all());

        Alert::success('Berhasil', 'Aturan Diskon berhasil diubah');
        return redirect()->route('diskon.index');
    }

    public function destroy(Diskon $diskon)
    {
        $diskon->delete();
        Alert::success('Berhasil', 'Aturan Diskon berhasil dihapus');
        return redirect()->route('diskon.index');
    }
}
