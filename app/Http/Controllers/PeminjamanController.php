<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use App\DataTables\PeminjamanDataTable;

class PeminjamanController extends Controller
{
    public function index(PeminjamanDataTable $dataTable)
    {
        return $dataTable->render('content.peminjaman.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'mdate'  => 'required|date_format:Y-m-d',
            'pdate'  => 'required|date_format:Y-m-d|after_or_equal:mdate',
            'jumlah' => 'required|min:1|numeric',
        ]);

        $peminjaman = peminjaman::create([
            'id_user'     => $request->id_user,
            'id_barang'   => $request->id_barang,
            'tgl_pinjam'  => $request->mdate,
            'tgl_kembali' => $request->pdate,
            'keterangan'  => $request->keterangan ?? '',
            'status'      => 'pending',
         ]);

        return response()->json($peminjaman);
    }

    public function edit(Request $request)
    {
        $where  = array('id' => $request->id);
        $barang = barang::where($where)->first();
        return response()->json($barang);
    }
}


