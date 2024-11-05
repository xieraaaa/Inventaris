<?php
namespace App\Imports;

use App\Models\merek;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MerekImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Check if the merek already exists to prevent duplicates
        $existingMerek = merek::where('merek', $row['merk'])->first();
        
        if ($existingMerek) {   
            return null;
        }

        return new merek([
            'merek'  => $row['merk'],
        ]);
    }
}
