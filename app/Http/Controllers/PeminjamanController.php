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
        // Decode the incoming JSON request data
        $data = json_decode($request->getContent(), true);

        // Ensure the 'data' key exists and is an array
        if (isset($data['data']) && is_array($data['data'])) {
            foreach ($data['data'] as $datum) {
                $id = $datum['id'];
                // Check if the 'kode_barang' exists in the database
                $barang = Barang::firstWhere('kode_barang', $id);

                if ($barang) {
                    // Create a new 'Peminjaman' record
                    Peminjaman::create([
                        'id_barang'   => $barang->id,
                        'id_user'     => Auth::user()->id,
                        'tgl_pinjam'  => $request['tgl_pinjam'],
                        'tgl_kembali' => $request['tgl_kembali'],
                        'keterangan'  => $request['keterangan'],
                        'status'      => 'pending'
                    ]);
                } else {
                    // Handle the case where the barang is not found
                    return response()->json(['error' => 'Barang with kode_barang ' . $id . ' not found'], 404);
                }
            }

            // Respond with a success message
            return response()->json(['message' => 'Peminjaman data added successfully']);
        } else {
            // Handle invalid or missing 'data' key in the request
            return response()->json(['error' => 'Invalid data format or missing data'], 400);
        }
    }

    /**
     * Show the user's history of peminjaman
     */
    public function riwayat()
    {
        return view('content.peminjaman.user');
    }

    /**
     * Fetch details of a barang by its kode_barang
     */
    public function show($code)
{
    // Find the product based on 'kode_barang' instead of 'id'
    $barang = Barang::where('kode_barang', $code)->first();

    if ($barang) {
        return response()->json([
            'id' =>$code,
            'name' => $barang->nama_barang,
            'jumlah' => $barang->jumlah,
        ]);
    } else {
        return response()->json(['message' => 'Barang not found'], 404);
    }
}

    /**
     * Store a new peminjaman entry
     */
    public function store(Request $request)
    {
        // Validate the request inputs
        $request->validate([
            'mdate'  => 'required|date_format:Y-m-d',
            'pdate'  => 'required|date_format:Y-m-d|after_or_equal:mdate',
            'jumlah' => 'required|min:1|numeric',
        ]);

        // Create the new peminjaman record
        $peminjaman = Peminjaman::create([
            'id_user'     => $request->id_user,
            'id_barang'   => $request->id_barang,
            'tgl_pinjam'  => $request->mdate,
            'tgl_kembali' => $request->pdate,
            'keterangan'  => $request->keterangan ?? '',
            'status'      => 'pending'
        ]);

        // Return the created peminjaman data
        return response()->json($peminjaman);
    }

    /**
     * Edit an existing peminjaman entry
     */
    public function edit(Request $request)
    {
        // Validate that 'id' is provided
        if (!$request->has('id')) {
            return response()->json(['error' => 'ID is required'], 400);
        }

        // Fetch the barang data based on 'id'
        $barang = Barang::where('id', $request->id)->first();

        // Check if the barang exists
        if ($barang) {
            return response()->json($barang);
        } else {
            return response()->json(['error' => 'Barang not found'], 404);
        }
    }
}
