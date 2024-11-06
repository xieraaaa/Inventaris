<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\barang as Barang;
use App\Models\kategori as Kategori;
use App\Models\Unit;
use App\Models\merek as Merek;

class UserController extends Controller
{
    private function getUserDashboard(bool $isAjax)
    {
        if ($isAjax)
        {
            return datatables()
                ->of(Barang::with(['kategori', 'unit', 'merek'])
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
                ->addColumn('action', 'content.barang.action.user')
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }

        $kategoris = Kategori::all();
        $units     = Unit::all();
        $mereks    = Merek::all();
        $barang    = Barang::first();

        $kondisiLabel = $barang ? ($barang->kondisi == 1 ? 'Baik' : 'Rusak') : 'N/A';
    
        return view('content.barang.user', compact('kategoris', 'units', 'mereks', 'kondisiLabel'));
    }
    
   public function index(Request $request)
   {
        $user = $request->user();
        
        if ($user->hasRole('user')) {
            return $this->getUserDashboard($request->ajax());
        }
        else if ($user->hasRole('admin')) {
            return view('content.dashboard.admin');
        }
    }
}
