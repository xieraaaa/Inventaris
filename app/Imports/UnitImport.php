<?php
namespace App\Imports;

use App\Models\Unit;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UnitImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Check if the merek already exists to prevent duplicates
        $existingUnit = unit::where('unit', $row['satuan'])->first();
        
        if ($existingUnit) {   
            return null;
        }

        return new Unit([
            'unit'  => $row['satuan'],
        ]);
    }
}
