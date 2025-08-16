<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Elemen;
use App\Models\Link;
use Illuminate\Support\Facades\Auth;

class EditorController extends Controller
{
    public function index($kode_unik, $nama_link)
    {
        $link = Link::where('kode_unik', $kode_unik)->where('nama_link', $nama_link)->first();
        return view('pageuser.editor.index', compact('link', 'kode_unik', 'nama_link'));
    }
}