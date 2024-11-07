<?php

namespace App\Http\Controllers;

use App\Imports\UnitImport;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

use App\Http\Controllers\Traits\Import;

class UnitController extends Controller
{
    use Import;

    public function __construct()
    {
        $this->importClass = UnitImport::class;
        $this->importSuccessRoute = 'unit';
    }
    
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            return datatables()->of(Unit::select('id', 'unit'))
                ->addColumn('action', 'content.unit.action')
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('content.unit.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $unitId = $request->id;

        /* Pastikan ada nilainya */
        $request->validate([
            'unit' => 'required',
        ]);

        $unit = Unit::updateOrCreate(
            [
                'id' => $unitId
            ],
            [
                'unit' => $request->unit,
            ]
        );

        return Response()->json($unit);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $where = array('id' => $request->id);
        $unit  = Unit::where($where)->first();

        return Response()->json($unit);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $unit = Unit::where('id', $request->id)->delete();
        
        if (!$unit) {
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
}
