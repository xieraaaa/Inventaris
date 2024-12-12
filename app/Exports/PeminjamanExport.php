<?php

namespace App\Exports;

use App\Models\Peminjaman;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PeminjamanExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Peminjaman::with(['user', 'detail.barang'])
            ->where('status', '!=', 1)
            ->where('status', '!=', 3)
            ->get();
    }

    public function headings(): array
    {
        return [
            'Nama Peminjam',
            'Tanggal Peminjaman',
            'Tanggal Pengembalian',
            'Keterangan',
            'Nama Barang',
            'Jumlah'
        ];
    }

    public function map($peminjaman): array
    {
        $rows = [];
        foreach ($peminjaman->detail as $detail) {
            $rows[] = [
                $peminjaman->user->name,
                $peminjaman->tgl_pinjam,
                $peminjaman->tgl_kembali,
                $peminjaman->keterangan,
                $detail->barang->nama_barang,
                $detail->jumlah
            ];
        }
        return $rows;
    }
}
