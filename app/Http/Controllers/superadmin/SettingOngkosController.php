<?php

namespace App\Http\Controllers\superadmin;

use App\Models\SettingOngkos;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\Controller;

class SettingOngkosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ongkos = SettingOngkos::first();
        if (!$ongkos) {
            $ongkos = SettingOngkos::create([
                'harga_per_meter' => 0,
                'latitude_lokasi_laundry' => null,
                'longitude_lokasi_laundry' => null,
            ]);
        }
        return view('pagesuperadmin.setting_ongkos.index', compact('ongkos'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(SettingOngkos $settingOngkos)
    {
        //
    }

    public function edit(SettingOngkos $settingOngkos)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'harga_per_meter' => 'required|numeric',
            'latitude_lokasi_laundry' => 'required',
            'longitude_lokasi_laundry' => 'required',
        ]);

        $ongkos = SettingOngkos::findOrFail($id);
        $ongkos->update($request->all());

        Alert::success('Berhasil', 'Setting Ongkos berhasil disimpan!');
        return redirect()->back();
    }

    public function destroy(SettingOngkos $settingOngkos)
    {
        //
    }
}
