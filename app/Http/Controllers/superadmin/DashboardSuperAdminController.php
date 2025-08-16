<?php

namespace App\Http\Controllers\superadmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Testimoni;
use App\Models\Link;

class DashboardSuperAdminController extends Controller
{
 public function index(){
    $pelanggan = User::where('role', 'user')->count();
    $link = Link::count();
    return view('pagesuperadmin.dashboard.index', compact('pelanggan', 'link'));
 }
}
