<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
	/**
	 * Menambahkan data pengajuan peminjaman ke database
	 */
	public function add(Request $request)
	{
		$data = json_decode($request->getContent(), true);

		foreach ($data['data'] as $datum) {
			$id     = $datum['id'];
			$barang = Barang::firstWhere('kode_barang', $id);

			Peminjaman::create([
				'id_barang'   => $barang['id'],
				'id_user'     => Auth::user()->id,
				'tgl_pinjam'  => $request['tgl_pinjam'],
				'tgl_kembali' => $request['tgl_kembali'],
				'keterangan'  => $request['keterangan'],
				'status'      => 'pending'
			]);
		}
	}

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


