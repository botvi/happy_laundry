<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\Testimoni;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ManageTestimoniController extends Controller
{
    public function index()
    {
        $testimonis = Testimoni::orderBy('created_at', 'desc')->with('user')->get();
        return view('pagesuperadmin.manage_testimoni.index', compact('testimonis'));
    }

    public function destroy($id)
    {
        $testimoni = Testimoni::find($id);

        if ($testimoni) {
            $testimoni->delete();
            Alert::success('Success', 'Testimoni berhasil dihapus');
        } else {
            Alert::error('Error', 'Testimoni tidak ditemukan');
        }

        return redirect()->route('manage-testimoni.index');
    }
}