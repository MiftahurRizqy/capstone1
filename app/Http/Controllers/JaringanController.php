<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JaringanController extends Controller
{
    public function pop()
    {
        return view('backend.pages.jaringan.pop');
    }

    public function node()
    {
        return view('backend.pages.jaringan.node');
    }

    public function kabkota()
    {
        return view('backend.pages.jaringan.kabkota');
    }
} 