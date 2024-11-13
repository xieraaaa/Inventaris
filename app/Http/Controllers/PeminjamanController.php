<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use App\DataTables\PeminjamanDataTable;

// debug
use Illuminate\Support\Facades\Log;

class PeminjamanController extends Controller
{
	/**
	 * Menambahkan data pengajuan peminjaman ke database
	 */
	public function add(Request $request)
	{
		// $data = $request->all();

<<<<<<< HEAD
    public function riwayat()
    {
        return view('content.peminjaman.user');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'mdate'  => 'required|date_format:Y-m-d',
            'pdate'  => 'required|date_format:Y-m-d|after_or_equal:mdate',
            'jumlah' => 'required|min:1|numeric',
        ]);
=======
		// foreach ($data as $unit)
		// {
		// 	Peminjaman::create()
		// }
	}
	
	public function store(Request $request)
	{
		$request->validate([
			'mdate'  => 'required|date_format:Y-m-d',
			'pdate'  => 'required|date_format:Y-m-d|after_or_equal:mdate',
			'jumlah' => 'required|min:1|numeric',
		]);
>>>>>>> 68ed8a2a0b5d2ade7b37046118ff7be1155a35ec

		$peminjaman = peminjaman::create([
			'id_user'     => $request->id_user,
			'id_barang'   => $request->id_barang,
			'tgl_pinjam'  => $request->mdate,
			'tgl_kembali' => $request->pdate,
			'keterangan'  => $request->keterangan ?? '',
			'status'      => 'pending'
		]);

		return response()->json($peminjaman);
	}

	public function edit(Request $request)
	{
		$where  = array('id' => $request->id);
		$barang = barang::where($where)->first();
		return response()->json($barang);
	}
}



