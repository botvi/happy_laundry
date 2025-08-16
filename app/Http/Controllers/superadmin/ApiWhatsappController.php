<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\WhatsappApi;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ApiWhatsappController extends Controller
{
    public function index()
    {
        $whatsappApi = WhatsappApi::first();
        return view('pagesuperadmin.api_whatsapp.index', compact('whatsappApi'));
    }

    public function storeorupdate(Request $request)
    {
        $whatsappApi = WhatsappApi::first();

        if ($whatsappApi) {
            // Update jika data sudah ada
            $whatsappApi->update($request->all());
            Alert::success('Success', 'Whatsapp Api berhasil diubah');
        } else {
            // Create jika data belum ada
            WhatsappApi::create($request->all());
            Alert::success('Success', 'Whatsapp Api berhasil ditambahkan');
        }

        return redirect()->route('whatsapp-api.index');
    }
}