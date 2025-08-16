<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Elemen;
use App\Models\Link;

class PreviewController extends Controller
{
    public function index($nama_link)
    {
        $link = Link::where('nama_link', $nama_link)->first();
        
        // Process order and hidden data
        $order = [];
        $hidden = [];
        $backgroundCustom = null;
        
        if ($link && isset($link->data_link['order'])) {
            $order = $link->data_link['order'];
        }
        
        if ($link && isset($link->data_link['hidden'])) {
            $hidden = $link->data_link['hidden'];
        }
        
        if ($link && isset($link->data_link['background_custom'])) {
            $backgroundCustom = $link->data_link['background_custom'];
        }
        
        return view('pageuser.preview.index', compact('link', 'order', 'hidden', 'backgroundCustom', 'nama_link'));
    }
}