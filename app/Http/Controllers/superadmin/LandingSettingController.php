<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\LandingSetting;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class LandingSettingController extends Controller
{
    public function index()
    {
        $setting = LandingSetting::first();
        return view('pagesuperadmin.landing_setting.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'jam_buka' => 'nullable',
            'jam_tutup' => 'nullable',
        ]);

        $setting = LandingSetting::first();
        if (!$setting) {
            $setting = new LandingSetting();
        }
        
        $setting->alamat_maps = $request->alamat_maps;
        $setting->jam_buka = $request->jam_buka;
        $setting->jam_tutup = $request->jam_tutup;
        $setting->running_text = $request->running_text;
        $setting->save();

        Alert::success('Berhasil', 'Pengaturan Landing Page berhasil diperbarui');
        return redirect()->route('landing-setting.index');
    }
}
