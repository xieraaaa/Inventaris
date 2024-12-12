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
        $request->validate([
            'unit-barang.*' => 'gte:0'
        ]);
        
        $pemindahan = Pemindahan::create([
            'tanggal'   => $request->tanggal,
            'asal'      => $request->asal,
            'tujuan'    => $request->tujuan,
            'deskripsi' => $request->deskripsi
        ]);

        $barangArray = $request->barang;
        $len = count($request->barang);
        for ($idx = 0; $idx < $len; ++$idx) {
            DetailPemindahan::create([
                'id_pemindahan' => $pemindahan->id,
                'id_barang'     => $barangArray[$idx],
                'jumlah' => 1
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

    public function edit($id)
{
    $pemindahan = Pemindahan::with('detail.barang')->find($id);
    if (!$pemindahan) {
        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }
    return response()->json($pemindahan);
}

public function destroy($id)
{
    $pemindahan = Pemindahan::find($id);

    if (!$pemindahan) {
        return response()->json(['error' => 'Data tidak ditemukan'], 404);
    }

    // Hapus data pemindahan
    $pemindahan->delete();

    return response()->json(['message' => 'Data berhasil dihapus']);
}

public function update(Request $request, $id)
{
    $pemindahan = Pemindahan::find($id);

    if (!$pemindahan) {
        return response()->json(['error' => 'Data tidak ditemukan'], 404);
    }

    // Update data pemindahan
    $pemindahan->tanggal = $request->tanggal;
    $pemindahan->asal = $request->asal;
    $pemindahan->tujuan = $request->tujuan;
    $pemindahan->deskripsi = $request->deskripsi;

    // Simpan perubahan
    $pemindahan->save();

    return response()->json(['message' => 'Data berhasil diperbarui']);
}


}
