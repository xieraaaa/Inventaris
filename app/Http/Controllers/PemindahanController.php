<?php

namespace App\Http\Controllers;

use App\Models\{
    Barang,
    DetailPemindahan,
    Pemindahan
};

use Illuminate\{
    Http\Request,
    Support\Facades\Log
};

class PemindahanController extends Controller
{
    public function index()
    {
        $koleksiBarang = Barang::all();
        return view('content.pemindahan.index', compact('koleksiBarang'));
    }

    public function store(Request $request)
    {
        $pemindahan = Pemindahan::create([
            'tanggal'   => $request->tanggal,
            'asal'      => $request->asal,
            'tujuan'    => $request->tujuan,
            'deskripsi' => $request->deskripsi
        ]);

        $barangArray = $request->barang;
        $jumlahArray = $request->jumlah;
        $len = count($request->barang);
        for ($idx = 0; $idx < $len; ++$idx) {
            DetailPemindahan::create([
                'id_pemindahan' => $pemindahan->id,
                'id_barang'     => $barangArray[$idx],
                'jumlah'        => $jumlahArray[$idx]
            ]);
        }
    }
}
