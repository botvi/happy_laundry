<?php

namespace App\Http\Controllers\user;

use App\Models\Testimoni;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class TestimoniController extends Controller
{
   public function store(Request $request)
   {
      $validator = Validator::make($request->all(), [
         'pesan' => 'required|string|min:255',
      ], [
         'pesan.min' => 'Testimoni minimal harus 255 karakter ya, bro/sis!',
      ]);

      if ($validator->fails()) {
         if ($validator->errors()->has('pesan')) {
            Alert::error('Oops!', $validator->errors()->first('pesan'))->persistent(true, false);
         }
         return redirect()->back()->withErrors($validator)->withInput();
      }

      $testimoni = Testimoni::create([
         'user_id' => auth()->user()->id,
         'pesan' => $request->pesan,
      ]);

      // SweetAlert untuk notifikasi sukses
      Alert::success('Mantap!', 'Testimoni kamu sudah terkirim, makasih banyak!')->persistent(true, false);

      return redirect()->back();
   }
}
