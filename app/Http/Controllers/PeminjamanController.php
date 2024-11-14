<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    /**
     * Menambahkan data pengajuan peminjaman ke database
     * 
     * Format data yang dikirim melalui parameter $request berupa:
     *      'data'
     *          sebuah array yang berisikan informasi mengenai barang-barang
     *          yang ingin dipinjam
     *
     *      'tgl_pinjam'
     *          tanggal peminjaman barang(-barang)
     *
     *      'tgl_kembali'
     *          tanggal dimana user berjanji (berdasarkan kesepakatan) untuk
     *          mengembalikan barang-barang yang dipinjam
     *
     *      'keterangan'
     *          keterangan mengenai kenapa barang-barang ingin dipinjam
     */
    public function add(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        // Ensure the 'data' key exists and is an array
        if (isset($data['data']) && is_array($data['data'])) {
            DB::transaction(function() use ($data, $request) {
                $idPeminjaman = Peminjaman::create([
                    'id_user'     => Auth::user()->id,
                    'tgl_pinjam'  => $data['tgl_pinjam'],
                    'tgl_kembali' => $data['tgl_kembali'],
                    'status'      => '',
                    'keterangan'  => $data['keterangan'],
                ])->id;

                foreach ($data['data'] as $datum) {
                    $id     = $datum['id'];
                    $barang = Barang::firstWhere('kode_barang', $id);

                    // Pastikan barang ada di database
                    if ($barang) {
                        DetailPeminjaman::create([
                            'id_peminjaman' => $idPeminjaman,
                            'id_barang'     => $barang->id,
                            'jumlah'        => $datum['jumlah']
                        ]);
                    } else {
                        // Handle the case where the barang is not found
                        return response()->json(['error' => 'Barang with kode_barang ' . $id . ' not found'], 404);
                    }
                }
            });

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
     * Mengambil detail sebuah barang dari kode barang
     */
    public function show($code)
    {
        // Find the product based on 'kode_barang' instead of 'id'
        $barang = Barang::where('kode_barang', $code)->first();

        if ($barang) {
            return response()->json([
                'id'     => $code,
                'name'   => $barang->nama_barang,
                'jumlah' => $barang->jumlah,
            ]);
        }
        else {
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
