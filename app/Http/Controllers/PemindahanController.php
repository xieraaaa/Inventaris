<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PemindahanController extends Controller
{
    public function index() {
        return view('content.pemindahan.index');
    }
}
