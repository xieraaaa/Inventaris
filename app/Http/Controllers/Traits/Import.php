<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use Maatwebsite\Excel\Facades\Excel;

trait Import {
    private string $importClass;
    private string $importSuccessRoute;

    /*
        Implementing classes must define the constructor to initialize the above
        properties through __construct
    */
    abstract function __construct();
    
    public function import(Request $request)
    {
        Log::info(get_class($request));
        
        // Validasi file yang diunggah
        $request->validate([
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);
    
        $file = $request->file('file');
    
        // Membuat nama file unik
        $nama_file = $file->hashName();
    
        // Menyimpan sementara file ke storage
        $path = $file->storeAs('excel/', $nama_file);
    
        // Import data dari file excel
        $import = Excel::import(new $this->importClass, storage_path('excel/' . $nama_file));
    
        // Menghapus file dari server setelah import
        Storage::delete($path);
    
        if ($import) {
            // Redirect jika berhasil
            return redirect()->route($this->importSuccessRoute)->with(['success' => 'Data Berhasil Diimport!']);
        } else {
            // Redirect jika gagal
            return redirect()->route($this->importSuccessRoute)->with(['error' => 'Data Gagal Diimport!']);
        }
    }
}
