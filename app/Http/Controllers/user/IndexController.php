<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Testimoni;
use App\Models\Brand;

class IndexController extends Controller
{
    public function index()
    {
        $testimonis = Testimoni::with('user')->get();
        $brands = Brand::where('status_brand', 'active')->get();
        return view('pageuser.landing.index', compact('testimonis', 'brands'));
    }
}