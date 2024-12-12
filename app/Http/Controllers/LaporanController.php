<?php

namespace App\Http\Controllers;

use App\Models\Pemindahan;
use App\Models\Peminjaman;

class LaporanController extends Controller
{
    private function getPeminjamanData()
    {
        $returnData = [];
        $peminjamanData = Peminjaman::where('status', '!=', 1)->where('status', '!=', 3)->get();

        foreach ($peminjamanData as $peminjaman) {
            $tmpData = [];

            $tmpData['peminjam'] = $peminjaman['user']['name'];
            $tmpData['tanggal_peminjaman'] = $peminjaman['tgl_pinjam'];
            $tmpData['tanggal_pengembalian'] = $peminjaman['tgl_kembali'];
            $tmpData['keterangan'] = $peminjaman['keterangan'];

            $tmpData['barang'] = $peminjaman->detail->map(function ($item) {
                return [
                    'nama_barang' => $item['barang']['nama_barang'],
                    'jumlah' => $item['jumlah']
                ];
            });

            array_push($returnData, $tmpData);
        }

        return $returnData;
    }

    private function getPemindahanData()
    {
        $returnData = [];
        $pemindahanData = Pemindahan::all();

        foreach ($pemindahanData as $pemindahan) {
            $tmpData = [];

            $tmpData['tanggal'] = $pemindahan['tanggal'];
            $tmpData['asal'] = $pemindahan['asal'];
            $tmpData['tujuan'] = $pemindahan['tujuan'];
            $tmpData['keterangan'] = $pemindahan['deskripsi'];

            $tmpData['barang'] = $pemindahan->detail->map(function ($item) {
                return [
                    'nama_barang' => $item['barang']['nama_barang']
                ];
            });

            array_push($returnData, $tmpData);
        }

        return $returnData;
    }

    public function index()
    {
        return view('content.laporan.index');
    }

    public function getPeminjamanView()
    {
        return view('content.laporan.data.peminjaman', [
            'data' => $this->getPeminjamanData()
        ]);
    }

    public function getPemindahanView()
    {
        return view('content.laporan.data.pemindahan', [
            'data' => $this->getPemindahanData()
        ]);
    }
}
