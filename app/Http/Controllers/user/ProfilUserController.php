<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Pelanggan;
use RealRashid\SweetAlert\Facades\Alert;

class ProfilUserController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pelanggan = Pelanggan::firstOrCreate(['user_id' => $user->id]);
        return view('pageuser.profil', compact('user', 'pelanggan'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'no_wa' => 'required|string|max:20',
            'alamat' => 'nullable|string'
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->no_wa = $request->no_wa;
        
        if($request->has('password') && !empty($request->password)){
            $user->password = bcrypt($request->password);
        }
        $user->save();

        $pelanggan = Pelanggan::firstOrCreate(['user_id' => $user->id]);
        $pelanggan->alamat = $request->alamat;
        $pelanggan->save();

        Alert::success('Berhasil', 'Profil berhasil diperbarui!');
        return redirect()->back();
    }
}
