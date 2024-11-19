<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

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
            DB::transaction(function () use ($data, $request) {
                $idPeminjaman = Peminjaman::create([
                    'id_user' => Auth::user()->id,
                    'tgl_pinjam' => $data['tgl_pinjam'],
                    'tgl_kembali' => $data['tgl_kembali'],
                    'status' => 'pending',
                    'keterangan' => $data['keterangan'],
                ])->id;

                foreach ($data['data'] as $datum) {
                    $id = $datum['id'];
                    $barang = Barang::firstWhere('kode_barang', $id);

                    // Pastikan barang ada di database
                    if ($barang) {
                        DetailPeminjaman::create([
                            'id_peminjaman' => $idPeminjaman,
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

    public function index()
    {
        return view('content.peminjaman.index');
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
                'id' => $code,
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
            'mdate' => 'required|date_format:Y-m-d',
            'pdate' => 'required|date_format:Y-m-d|after_or_equal:mdate',
            'jumlah' => 'required|min:1|numeric',
        ]);

        // Create the new peminjaman record
        $peminjaman = Peminjaman::create([
            'id_user' => $request->id_user,
            'id_barang' => $request->id_barang,
            'tgl_pinjam' => $request->mdate,
            'tgl_kembali' => $request->pdate,
            'keterangan' => $request->keterangan ?? '',
            'status' => 'pending'
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

    public function acceptPeminjaman(Request $request, $id)
    {
        $peminjaman = Peminjaman::find($id);

        if ($peminjaman) {
            // Update the status to 'di pinjam'
            $peminjaman->status = 'Approved';
            $peminjaman->save();

            $details = $peminjaman->detail;

            foreach ($details as $detail) {
                $barang = $detail->barang;
                $barang->jumlah = $barang->jumlah - $detail->jumlah;
                $barang->save(); 
            }

            return response()->json(['message' => 'Peminjaman status updated to di pinjam']);
        } else {
            return response()->json(['error' => 'Peminjaman gagal ditemukan!'], 404);
        }
    }

    public function rejectPeminjaman(Request $request, $id)
    {
        // Cari peminjaman berdasarkan ID
        $peminjaman = peminjaman::find($id);
    
        if ($peminjaman) {
            // Hapus data peminjaman beserta detailnya
            DB::transaction(function () use ($peminjaman) {
                // Hapus detail peminjaman
                $peminjaman->detail()->delete();
    
                // Hapus peminjaman utama
                $peminjaman->delete();
            });
    
            return response()->json(['message' => 'Peminjaman has been deleted successfully']);
        } else {
            return response()->json(['error' => 'Peminjaman not found'], 404);
        }
    }
    
    public function history()
    {
        $data = [];

        $peminjamanData = Peminjaman::with(['detail', 'user'])->where('status', 'pending')->where('id_user', Auth::user()->id)->get();
        foreach ($peminjamanData as $peminjaman) {
            $buffer = [];

            $buffer['id']          = $peminjaman['id'];
            $buffer['nama_user']   = $peminjaman->user->name;
            $buffer['tgl_pinjam']  = $peminjaman['tgl_pinjam'];
            $buffer['tgl_kembali'] = $peminjaman['tgl_kembali'];
            $buffer['keterangan']  = $peminjaman['keterangan'];
            $buffer['status']      = $peminjaman['status'];
            $buffer['barang']      = $peminjaman->detail->map(function ($item) {
                $item->barang->setVisible(['nama_barang']);
                
                $barang = $item->barang->toArray();

                $barang['jumlah'] = $item->jumlah;

                return $barang;
            });

            array_push($data, $buffer);
        }

        return $data;
    }

    /**
     * Untuk mengambil data peminjaman berdasarkan ID yang diberikan.
     * Diakses dari rute 'peminjaman/detail/{id}/'
     */ 
    public function getDetails()
    {
        $data = [];

        $peminjamanData = Peminjaman::with(['detail', 'user'])->where('status', 'pending')->get();
        foreach ($peminjamanData as $peminjaman) {
            $buffer = [];

            $buffer['id']          = $peminjaman['id'];
            $buffer['nama_user']   = $peminjaman->user->name;
            $buffer['tgl_pinjam']  = $peminjaman['tgl_pinjam'];
            $buffer['tgl_kembali'] = $peminjaman['tgl_kembali'];
            $buffer['keterangan']  = $peminjaman['keterangan'];
            $buffer['status']      = $peminjaman['status'];
            $buffer['barang']      = $peminjaman->detail->map(function ($item) {
                $item->barang->setVisible(['nama_barang']);
                
                $barang = $item->barang->toArray();

                $barang['jumlah'] = $item->jumlah;

                return $barang;
            });

            array_push($data, $buffer);
        }

        return $data;
    }

    public function detailAdmin()
    {

        $data = [];

        $peminjamanData = Peminjaman::with(['detail', 'user'])
            ->whereIn('status', ['Approved', 'di pinjam'])
            ->get();
        foreach ($peminjamanData as $peminjaman) {
            $buffer = [];

            $buffer['id']          = $peminjaman['id'];
            $buffer['nama_user']   = $peminjaman->user->name;
            $buffer['tgl_pinjam']  = $peminjaman['tgl_pinjam'];
            $buffer['tgl_kembali'] = $peminjaman['tgl_kembali'];
            $buffer['keterangan'] = $peminjaman['keterangan'];
            $buffer['status'] = $peminjaman['status'];
            $buffer['barang'] = $peminjaman->detail->map(function ($item) {
                $item->barang->setVisible(['nama_barang']);
                
                $barang = $item->barang->toArray();

                $barang['jumlah'] = $item->jumlah;

                return $barang;
            });

            array_push($data, $buffer);
        }

        return $data;
    }

    public function AcceptStatus(Request $request, $id)
    {
        // Find the peminjaman entry by ID
        $peminjaman = peminjaman::find($id);

        if ($peminjaman && $peminjaman->status !== 'di pinjam') {
            // Update the status to 'di pinjam'
            $peminjaman->status = 'di pinjam';
            $peminjaman->save();

            return response()->json(['message' => 'Peminjaman status updated to di pinjam']);
        } else {
            return response()->json(['error' => 'Peminjaman not found or already di pinjam'], 404);
        }
    }

    public function peminjamanKembali(Request $request, $id)
    {
        $peminjaman = Peminjaman::find($id);

        if ($peminjaman) {
            // Update the status to 'di pinjam'
            $peminjaman->status = 'di kembalikan';
            $peminjaman->save();

            $details = $peminjaman->detail;

            foreach ($details as $detail) {
                $barang = $detail->barang;
                $barang->jumlah = $barang->jumlah + $detail->jumlah;
                $barang->save(); 
            }

            return response()->json(['message' => 'Peminjaman status updated to di pinjam']);
        } else {
            return response()->json(['error' => 'Peminjaman gagal ditemukan!'], 404);
        }
    }
}
