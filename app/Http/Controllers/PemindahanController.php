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

    public function viewriwayat()
    {
        return view('content.pemindahan.riwayat');
    }


    public function riwayat()
    {
        $pemindahan = Pemindahan::all(); // Ambil semua data dari model Pemindahan
        return response()->json($pemindahan);
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

    public function getDetails()
    {
        $data = [];

        $PemindahanData = Pemindahan::with(['detail'])->get();
        foreach ($PemindahanData as $Pemindahan) {
            $buffer = [];

            $buffer['id']        = $Pemindahan['id'];
            $buffer['tanggal']   = $Pemindahan['tanggal'];
            $buffer['asal']      = $Pemindahan['asal'];
            $buffer['tujuan']    = $Pemindahan['tujuan'];
            $buffer['deskripsi'] = $Pemindahan['deskripsi'];
            $buffer['barang']    = $Pemindahan->detail->map(function ($item) {
                $item->barang->setVisible(['nama_barang']);
                
                $barang = $item->barang->toArray();

                $barang['jumlah'] = $item->jumlah;

                return $barang;
            });

            array_push($data, $buffer);
        }

        return $data;
    }
}
