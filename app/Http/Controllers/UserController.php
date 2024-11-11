<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Unit;
use App\Models\Merek;

use App\DataTables\Peminjaman;
use App\DataTables\PeminjamanDataTable;
use App\Http\Controllers\PeminjamanController;

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

        $koleksiBarang = Barang::all();
    
        return view('content.barang.user', compact('kategoris', 'units', 'mereks', 'kondisiLabel', 'koleksiBarang'));
    }

    public function getSuperadminDashboard()
    {
        return app(PeminjamanController::class)->index(new PeminjamanDataTable());
    }
    
   public function index(Request $request)
   {
        $user = $request->user();
        
        if ($user->hasRole('user'))
        {
            return $this->getUserDashboard($request->ajax());
        }
        else if ($user->hasRole('admin'))
        {
            return view('content.dashboard.admin');
        }
        else if ($user->hasRole('superadmin'))
        {
            return $this->getSuperadminDashboard();
        }
    }

    public function store(Request $request)
    {
        $request->validate([                                                        
            'mdate'  => 'required|date_format:Y-m-d',
            'pdate'  => 'required|date_format:Y-m-d|after_or_equal:mdate',
            'jumlah' => 'required|min:1|numeric',
        ]);
    }

    public function edit(Request $request)
    {
        $where  = array('id' => $request->id);
        $barang = barang::where($where)->first();

        return Response()->json($barang);
    }
}
