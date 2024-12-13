<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\kategori;
use App\Models\merek;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController {
	private function getUserDashboard()
	{
		$pages = ceil(count(Barang::all()) / 20);
		
		return view('content.dashboard.user', ['pages' => $pages]);
	}

	private function getAdminDashboard(Request $request)
	{
		if ($request->ajax()) {
            // Query utama untuk mengambil data barang
        $query = Barang::with(['unit', 'kategori', 'merek', 'unitBarang']);
    
        // Filter pencarian
        if ($searchValue = $request->input('search.value')) {
            $query->where('nama_barang', 'like', "%$searchValue%")
                ->orWhereHas('unit', function ($q) use ($searchValue) {
                    $q->where('unit', 'like', "%$searchValue%");
                })
                ->orWhereHas('kategori', function ($q) use ($searchValue) {
                    $q->where('kategori', 'like', "%$searchValue%");
                })
                ->orWhereHas('merek', function ($q) use ($searchValue) {
                    $q->where('merek', 'like', "%$searchValue%");
                });
        }
    
        // Total record tanpa filter
        $totalRecords = Barang::count();
        $filteredRecords = $query->count();
    
        // Pagination (start dan length)
        $barangData = $query->skip($request->input('start'))->take($request->input('length'))->get();
    
        // Format data
        $data = $barangData->map(function ($datum) {
            return [
                'id' => $datum->id,
                'nama_barang' => $datum->nama_barang,
                'unit' => $datum->unit->unit,
                'merek' => $datum->merek->merek,
                'kategori' => $datum->kategori->kategori,
                'unitBarang' => $datum->unitBarang,
                'jumlah' => count($datum->unitBarang),
            ];
        });
    
        // Response ke DataTables
        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
        ]);
        }

        // Fetch necessary data for the view
        $kategoris = kategori::all();  // Fetch all categories
        $units = Unit::all();          // Fetch all units
        $mereks = merek::all();        // Fetch all brands
        $barang = Barang::first();     // Fetch the first barang (item)
        $kondisiLabel = $barang ? ($barang->kondisi == 1 ? 'Baik' : 'Rusak') : 'N/A';  // Label for condition

        // Return the view with the fetched data
        return view('content.barang.admin', compact('kategoris', 'units', 'mereks', 'kondisiLabel'));
	}

	private function getSuperadminDashboard()
	{
		return view('content.dashboard.superadmin');
	}
	
	public function index(request $request)
	{
		$user = Auth::user();

		if ($user->hasRole('user')) {
			return $this->getUserDashboard();
		}
		elseif ($user->hasRole('admin')) {
			return $this->getAdminDashboard($request);
		}
		elseif ($user->hasRole('superadmin')) {
			return $this->getSuperadminDashboard();
		}
	}
}
