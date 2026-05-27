<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\LandingSetting;
use App\Models\PaketLaundry;

class IndexController extends Controller
{
    public function index()
    {
        $setting = LandingSetting::first();
        $pakets = PaketLaundry::all();
        
        return view('pageuser.landing.index', compact('setting', 'pakets'));
    }
}