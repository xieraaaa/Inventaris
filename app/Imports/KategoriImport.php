<?php
namespace App\Imports;

use App\Models\kategori;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class kategoriImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Check if the kategori already exists to prevent duplicates
        $existingkategori = kategori::where('kategori', $row['kategori'])->first();
        
        if ($existingkategori) {   
            return null;
        }

        return new kategori([
            'kategori'  => $row['kategori'],
        ]);
    }
}
