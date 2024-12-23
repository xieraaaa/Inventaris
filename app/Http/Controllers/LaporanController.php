<?php

namespace App\Http\Controllers;

use App\Models\Pemindahan;
use App\Models\Peminjaman;
use App\Exports\PeminjamanExport;
use App\Exports\PemindahanExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade as PDF;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

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

    public function exportPeminjamanToExcel()
    {
        return Excel::download(new PeminjamanExport, 'laporan_peminjaman.xlsx');
    }

    public function exportPemindahanToExcel()
    {
        return Excel::download(new PemindahanExport, 'laporan_pemindahan.xlsx');
    }

    public function exportPeminjamanPDF()
    {
        $data = $this->getPeminjamanData();
        
        $pdf = FacadePdf::loadView('content.laporan.data.peminjaman', compact('data'));
        return $pdf->download('laporan_peminjaman.pdf');
    }

    public function exportPemindahanPDF()
    {
        $data = $this->getPemindahanData();

        $pdf = FacadePdf::loadView('content.laporan.data.pemindahan', compact('data'));
        return $pdf->download('laporan_pemindahan.pdf');
    }
}
