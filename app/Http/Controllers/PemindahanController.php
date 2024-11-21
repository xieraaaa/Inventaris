<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailPemindahan;
use App\Models\Pemindahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\log;

class PemindahanController extends Controller
{
    public function index() {
        $koleksiBarang = Barang::all();
        return view('content.pemindahan.index', compact('koleksiBarang'));


    }

    public function store(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        // Ensure the 'data' key exists and is an array
        if (isset($data['data']) && is_array($data['data'])) {
            DB::transaction(function () use ($data, $request) {
                $idpemindahan =Pemindahan::create([
                    'tanggal' => $data['tanggal'],
                    'asal' => $data['asal'],
                    'tujuan' => $data['tujuan'],
                    'deskripsi' => $data['deskripsi'],
                ])->id;

                foreach ($data['data'] as $datum) {
                    $id = $datum['id'];
                    $barang = Barang::firstWhere('kode_barang', $id);

                    // Pastikan barang ada di database
                    if ($barang) {
                        DetailPemindahan::create([
                            'id_pemindahan' => $idpemindahan,
                            'id_barang' => $barang->id,
                            'jumlah' => $datum['jumlah']
                        ]);
                    } else {
                        // Handle the case where the barang is not found
                        return response()->json(['error' => 'Barang with kode_barang ' . $id . ' not found'], 404);
                    }
                }
            });

            // Respond with a success message
            return response()->json(['message' => 'pemindahanPemindahan data added successfully']);
        } else {
            // Handle invalid or missing 'data' key in the request
            return response()->json(['error' => 'Invalid data format or missing data'], 400);
        }
    }
}
