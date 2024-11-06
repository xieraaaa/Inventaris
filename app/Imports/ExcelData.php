<?php

namespace App\Imports;

use App\Models\barang as Barang;
use App\Models\kategori as Kategori;
use App\Models\merek as Merek;
use App\Models\Unit;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ExcelData implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collections)
    {
        foreach ($collections as $collection)
        {
            $kategori = Kategori::where('kategori', $collection['kategori'])->first();
            $merek    = Merek::where('merek', $collection['merk'])->first();
            $unit     = Unit::where('unit', $collection['satuan'])->first();
            $kondisi  = $collection['kondisi'];
            
            // if (is_null($kategori))
            // {
            //     $kategori = Kategori::create([
            //         'kategori' => $collection['kategori']
            //     ]);
            // }

            // if (is_null($merek))
            // {
            //     $merek = Merek::create([
            //         'merek' => $collection['merk']
            //     ]);
            // }

            // if (is_null($unit))
            // {
            //     $unit = Unit::create([
            //         'unit' => $collection['satuan']
            //     ]);
            // }

            Barang::create([
                'kode_barang' => $collection['kode_barang'],
                'nama_barang' => $collection['nama_barang'],
                'id_kategori' => $kategori['id'],
                'id_unit'     => $unit['id'],
                'id_merek'    => $merek['id'],
                'jumlah'      => $collection['jumlah'],
                'kondisi'     => $kondisi === 'Baik' ? 1 : 0,
                'keterangan'  => $collection['keterangan']
            ]);
        }
    }
}