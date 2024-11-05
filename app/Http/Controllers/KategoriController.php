<?php

namespace App\Http\Controllers;


use App\Models\kategori;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\kategoriImport;
use Illuminate\Support\Facades\Storage;


use Datatables;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            //return datatables()->of(kategori::select('*'))
            return datatables()->of(kategori::select('id', 'kategori'))
                ->addColumn('action', 'content.kategori.kategori-action')
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('content.kategori.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $kategoriId = $request->id;

        $request->validate([
            'kategori' => 'required',
        ]);

        $kategori = kategori::updateOrCreate(
            [
                'id' => $kategoriId
            ],
            [
                'kategori' => $request->kategori,
            ]
        );

        return Response()->json($kategori);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $where = array('id' => $request->id);
        $kategori  = kategori::where($where)->first();

        return Response()->json($kategori);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $kategori = kategori::where('id', $request->id)->delete();

        
        if (!$kategori) {
            return response()->json([
                "status" => "failed",
                "msg" => "Something went wrong!"
            ], 210);
        } else {
            return response()->json([
                "status" => "success",
                "msg" => "Product Deleted Successfully"
            ], 201);
        }
    }


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
        $import = Excel::import(new KategoriImport(), storage_path('app/public/excel/' . $nama_file));
    
        // Menghapus file dari server setelah import
        Storage::delete($path);
    
        if ($import) {
            // Redirect jika berhasil
            return redirect()->route('kategori')->with(['success' => 'Data Berhasil Diimport!']);
        } else {
            // Redirect jika gagal
            return redirect()->route('kategori')->with(['error' => 'Data Gagal Diimport!']);
        }
    }

}
    
    

