<?php

namespace App\Http\Controllers;


use App\Models\merek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MerekImport;


use Datatables;

class MerekController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            //return datatables()->of(merek::select('*'))
            return datatables()->of(merek::select('id', 'merek'))
                ->addColumn('action', 'content.merek.merek-action')
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('content.merek.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $merekId = $request->id;

        $request->validate([
            'merek' => 'required',
        ]);

        $merek = merek::updateOrCreate(
            [
                'id' => $merekId
            ],
            [
                'merek' => $request->merek,
            ]
        );

        return Response()->json($merek);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\merek  $merek
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $where = array('id' => $request->id);
        $merek  = merek::where($where)->first();

        return Response()->json($merek);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\merek  $merek
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $merek = merek::where('id', $request->id)->delete();

        
        if (!$merek) {
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
        $import = Excel::import(new MerekImport(), storage_path('app/public/excel/' . $nama_file));
    
        // Menghapus file dari server setelah import
        Storage::delete($path);
    
        if ($import) {
            // Redirect jika berhasil
            return redirect()->route('merek')->with(['success' => 'Data Berhasil Diimport!']);
        } else {
            // Redirect jika gagal
            return redirect()->route('merek')->with(['error' => 'Data Gagal Diimport!']);
        }
    }
    
}
    
    

