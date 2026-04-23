<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaketLaundry;

class DaftarPaketController extends Controller
{
    public function index()
    {
        $pakets = PaketLaundry::all();
        return view('pageuser.daftar_paket.index', compact('pakets'));
    }
}
