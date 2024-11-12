<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;

class SuperAdminController extends Controller
{
    /**
     * Memberikan HTML untuk dashboard superadmin
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('content.dashboard.superadmin');
    }

    /**
     * Memberikan data (permohonan peminjaman) untuk dashboard superadmin dalam
     * bentuk JSON
     * 
     * @return string
     */
    public function data()
    {
        return Peminjaman::all()->toJson();
    }
}
