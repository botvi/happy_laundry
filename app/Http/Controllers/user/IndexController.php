<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index()
    {
        return view('pageuser.landing.index');
    }
}