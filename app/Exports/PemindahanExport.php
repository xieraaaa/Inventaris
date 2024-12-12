<?php

namespace App\Exports;

use App\Models\Pemindahan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PemindahanExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Pemindahan::with(['detail.barang'])->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Asal',
            'Tujuan',
            'Keterangan',
            'Nama Barang'
        ];
    }

    public function map($pemindahan): array
    {
        $rows = [];
        foreach ($pemindahan->detail as $detail) {
            $rows[] = [
                $pemindahan->tanggal,
                $pemindahan->asal,
                $pemindahan->tujuan,
                $pemindahan->deskripsi,
                $detail->barang->nama_barang
            ];
        }
        return $rows;
    }
}
