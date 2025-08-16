<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::orderBy('created_at', 'desc')->get();
        return view('pagesuperadmin.brand.index', compact('brands'));
    }

    public function create()
    {
        return view('pagesuperadmin.brand.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'logo_brand' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'link_brand' => 'required|url',
            'status_brand' => 'required|in:active,inactive',
        ], [
            'logo_brand.required' => 'Logo brand wajib diupload',
            'logo_brand.image' => 'File harus berupa gambar',
            'logo_brand.mimes' => 'Format file harus jpeg, png, jpg, gif, atau svg',
            'logo_brand.max' => 'Ukuran file maksimal 2MB',
            'link_brand.required' => 'Link brand wajib diisi',
            'link_brand.url' => 'Link brand harus berupa URL yang valid',
            'status_brand.required' => 'Status brand wajib dipilih',
            'status_brand.in' => 'Status brand tidak valid',
        ]);

        if ($validator->fails()) {
            Alert::error('Error', $validator->errors()->first());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $brand = new Brand();
            $brand->link_brand = $request->link_brand;
            $brand->status_brand = $request->status_brand;

            if ($request->hasFile('logo_brand')) {
                $file = $request->file('logo_brand');
                $filename = 'logo_brand_' . Str::random(10) . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/logo_brand'), $filename);
                $brand->logo_brand = $filename;
            }

            $brand->save();

            Alert::success('Sukses', 'Brand berhasil ditambahkan');
            return redirect()->route('brand.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Terjadi kesalahan saat menyimpan brand');
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('pagesuperadmin.brand.edit', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'logo_brand' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'link_brand' => 'required|url',
            'status_brand' => 'required|in:active,inactive',
        ], [
            'logo_brand.image' => 'File harus berupa gambar',
            'logo_brand.mimes' => 'Format file harus jpeg, png, jpg, gif, atau svg',
            'logo_brand.max' => 'Ukuran file maksimal 2MB',
            'link_brand.required' => 'Link brand wajib diisi',
            'link_brand.url' => 'Link brand harus berupa URL yang valid',
            'status_brand.required' => 'Status brand wajib dipilih',
            'status_brand.in' => 'Status brand tidak valid',
        ]);

        if ($validator->fails()) {
            Alert::error('Error', $validator->errors()->first());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $brand->link_brand = $request->link_brand;
            $brand->status_brand = $request->status_brand;

            if ($request->hasFile('logo_brand')) {
                // Hapus file lama jika ada
                if ($brand->logo_brand && file_exists(public_path('uploads/logo_brand/' . $brand->logo_brand))) {
                    unlink(public_path('uploads/logo_brand/' . $brand->logo_brand));
                }

                $file = $request->file('logo_brand');
                $filename = 'logo_brand_' . Str::random(10) . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/logo_brand'), $filename);
                $brand->logo_brand = $filename;
            }

            $brand->save();

            Alert::success('Sukses', 'Brand berhasil diubah');
            return redirect()->route('brand.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Terjadi kesalahan saat mengubah brand');
            return redirect()->back()->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $brand = Brand::findOrFail($id);
            
            // Hapus file logo jika ada
            if ($brand->logo_brand && file_exists(public_path('uploads/logo_brand/' . $brand->logo_brand))) {
                unlink(public_path('uploads/logo_brand/' . $brand->logo_brand));
            }
            
            $brand->delete();
            Alert::success('Sukses', 'Brand berhasil dihapus');
        } catch (\Exception $e) {
            Alert::error('Error', 'Terjadi kesalahan saat menghapus brand');
        }
        
        return redirect()->route('brand.index');
    }

    public function toggleStatus($id)
    {
        try {
            $brand = Brand::findOrFail($id);
            $brand->status_brand = $brand->status_brand === 'active' ? 'inactive' : 'active';
            $brand->save();
            
            $status = $brand->status_brand === 'active' ? 'diaktifkan' : 'dinonaktifkan';
            Alert::success('Sukses', "Brand berhasil {$status}");
        } catch (\Exception $e) {
            Alert::error('Error', 'Terjadi kesalahan saat mengubah status brand');
        }
        
        return redirect()->route('brand.index');
    }
}