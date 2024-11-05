<?php

namespace App\Http\Controllers;


use App\Models\barang;
use App\Models\kategori;
use App\Models\merek;
use App\Models\Unit;
use Illuminate\Http\Request;


use Datatables;

class barangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            //return datatables()->of(barang::select('*'))
            return datatables()->of(Barang::with(['kategori', 'unit', 'merek'])
            ->select('id', 'kode_barang','nama_barang','id_kategori','id_unit','id_merek','kondisi','keterangan',))
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
                    
                    return $barang->kondisi == 1 ? 'baik' : 'rusak';
                })
                ->addColumn('action', 'content.barang.barang-action')
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
        

        $kategoris = kategori::all();
        $units = Unit::all();
        $mereks = Merek::all();
        $barang = Barang::first();
        $kondisiLabel = $barang ? ($barang->kondisi == 1 ? 'baik' : 'rusak') : 'N/A';
    
        return view('content.barang.index', compact('kategoris', 'units', 'mereks', 'kondisiLabel'));
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
}
