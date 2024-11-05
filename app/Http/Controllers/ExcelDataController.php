<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

use App\Imports\ExcelData;

class ExcelDataController extends Controller
{
    public function import(Request $request)
    {
        // Validasi file yang diunggah
        $request->validate([
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);
    
        $file = $request->file('file');
    
        // Membuat nama file unik
        $nama_file = $file->hashName();
    
        // Menyimpan sementara file ke storage
        $path = $file->storeAs('public/excel/', $nama_file);
    
        // Import data dari file excel
        $import = Excel::import(new ExcelData, storage_path('app/public/excel/' . $nama_file));
    
        // Menghapus file dari server setelah import
        Storage::delete($path);
    
        if ($import) {
            // Redirect jika berhasil
            return redirect()->route('barang')->with(['success' => 'Data Berhasil Diimport!']);
        } else {
            // Redirect jika gagal
            return redirect()->route('barang')->with(['error' => 'Data Gagal Diimport!']);
        }
    }
}
