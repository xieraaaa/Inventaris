<?php

namespace App\Http\Controllers;


use App\Models\barang;
use App\Models\kategori;
use App\Models\merek;
use App\Models\Unit;
use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

use App\Imports\ExcelData;

class barangController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            //return datatables()->of(barang::select('*'))
            return datatables()->of(Barang::with(['kategori', 'unit', 'merek'])
            ->select('id', 'kode_barang','nama_barang','id_kategori','id_unit','id_merek','jumlah','kondisi','keterangan',))
                ->addColumn('kategori', function ($barang) {
                    return $barang->kategori ? $barang->kategori->kategori : '-';
                })
                ->addColumn('unit', function ($barang) {
                    return $barang->unit ? $barang->unit->unit : '-';
                })
                ->addColumn('merek', function ($barang) {
                    return $barang->merek ? $barang->merek->merek : '-';
                })
                ->addColumn('kondisi_label', function ($barang) {
                    
                    return $barang->kondisi == 1 ? 'Baik' : 'Rusak';
                })
                ->addColumn('action', 'content.barang.action.admin')
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
        

        $kategoris = kategori::all();
        $units = Unit::all();
        $mereks = Merek::all();
        $barang = Barang::first();
        $kondisiLabel = $barang ? ($barang->kondisi == 1 ? 'Baik' : 'Rusak') : 'N/A';
    
        return view('content.barang.admin', compact('kategoris', 'units', 'mereks', 'kondisiLabel'));
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $barangId = $request->id;

        $request->validate([
            'kode_barang' => 'required',
            'nama_barang' => 'required',
            'id_kategori' => 'required',
            'id_unit' => 'required',
            'id_merek' => 'required',
            'jumlah' => 'required',
            'kondisi' => 'required',
            'keterangan' => 'required',
            
        ]);

        $barang = barang::updateOrCreate(
            [
                'id' => $barangId
            ],
            [
                'kode_barang' => $request->kode_barang,
                'nama_barang' => $request->nama_barang,
                'id_kategori' => $request->id_kategori,
                'id_unit' => $request->id_unit,
                'id_merek' => $request->id_merek,
                'jumlah' => $request->jumlah,
                'kondisi' => $request->kondisi,
                'keterangan' => $request->keterangan,
            ]
        );

        return Response()->json($barang);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $where = array('id' => $request->id);
        $barang  = barang::where($where)->first();

        return Response()->json($barang);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $barang = barang::where('id', $request->id)->delete();


        if (!$barang) {
            return response()->json([
                "status" => "failed",
                "msg" => "Something went wrong!"
            ], 210);
        } else {
            return response()->json([
                "status" => "success",
                "msg" => "barang Deleted Successfully"
            ], 201);
        }
    }

    public function import(Request $request)
    {
        // Validasi file yang diunggah
        $request->validate([
            'data_excel' => 'required|mimes:csv,xls,xlsx'
        ]);
    
        $file = $request->file('data_excel');
    
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
