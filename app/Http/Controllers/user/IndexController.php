<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Testimoni;

class IndexController extends Controller
{
    public function index()
    {
        $testimonis = Testimoni::with('user')->get();
        return view('pageuser.landing.index', compact('testimonis'));
    }
}