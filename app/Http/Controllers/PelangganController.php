<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function personal()
    {
        return view('backend.pages.pelanggan.personal');
    }

    public function perusahaan()
    {
        return view('backend.pages.pelanggan.perusahaan');
    }
} 