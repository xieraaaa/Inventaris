<?php

namespace App\Http\Controllers;

use App\Models\{
    Barang,
    DetailPeminjaman,
    Peminjaman
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Events\PeminjamanInvoked as PeminjamanInvokedEvent;
use Illuminate\Contracts\Database\Eloquent\Builder;

enum PeminjamanStatus: int {
    case Pending = 1;
    case Approved = 2;
    case Rejected = 3;
    case Borrowed = 4;
    case Returned = 5;
}

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

        // Pastikan data ada
        if (!isset($data['data']) || !is_array($data['data'])) {
            return response()->json(['error' => 'Invalid data format or missing data'], 400);
        }

        DB::transaction(function() use($data) {
            $peminjaman = Peminjaman::create([
                'id_user' => Auth::user()['id'],
                'tgl_pinjam' => $data['tgl_pinjam'],
                'tgl_kembali' => $data['tgl_kembali'],
                'status' => PeminjamanStatus::Pending,
                'keterangan' => $data['keterangan']
            ]);

            foreach ($data['data'] as $datum) {
                $barang = Barang::firstWhere('id', $datum['id']);

                if (is_null($barang)) {
                    return response()->json(['error' => 'Barang with kode_barang ' . $datum['id'] . ' not found'], 404);
                }

                DetailPeminjaman::create([
                    'id_peminjaman' => $peminjaman['id'],
                    'id_barang' => $barang['id'],
                    'jumlah' => $datum['jumlah']
                ]);
            }

            broadcast(new PeminjamanInvokedEvent($peminjaman))->toOthers();
        });

        // Respon dengan notifikasi sukses
        return response()->json(['message' => 'Peminjaman data added successfully']);
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
        $peminjaman = Peminjaman::with('detail')->get();

        return view('content.peminjaman.index', compact('peminjaman'));
    }

    public function superadmin()
    {
        return view('content.peminjaman.superadmin');
    }

    public function admin()
    {
        return view('content.peminjaman.admin');
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

    public function acceptPeminjaman($id)
    {
        $peminjaman = Peminjaman::find($id);

        if ($peminjaman) {
            $peminjaman->status = PeminjamanStatus::Approved;
            $peminjaman->save();

            $details = $peminjaman->detail;

            foreach ($details as $detail) {
                $barang = $detail->barang;
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

        $peminjamanData = Peminjaman::with(['detail', 'user'])
        ->where('id_user', Auth::user()->id)
        ->where(function ($query) {
            $query->where('status', PeminjamanStatus::Borrowed)
                ->orWhere('status', PeminjamanStatus::Returned)
                ->orWhere('status', PeminjamanStatus::Approved);
        })
            ->get();

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

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|number',
        ]);

        $peminjaman = Peminjaman::findOrFail($id); // Pastikan model Peminjaman sudah ada
        $peminjaman->status = $validated['status'];
        $peminjaman->save();

        return response()->json(['message' => 'Status peminjaman berhasil diperbarui.']);
    }

    /**
     * Mengambil pengajuan peminjaman yang perlu diproses oleh superadmin
     */
    public function getDetails()
    {
        $data = [];

        $peminjamanData = Peminjaman::with(['detail', 'user'])->where('status', PeminjamanStatus::Pending)->get();
        foreach ($peminjamanData as $peminjaman) {
            $buffer = [];

            $buffer['id']          = $peminjaman['id'];
            $buffer['nama_user']   = $peminjaman->user->name;
            $buffer['tgl_pinjam']  = $peminjaman['tgl_pinjam'];
            $buffer['tgl_kembali'] = $peminjaman['tgl_kembali'];
            $buffer['keterangan']  = $peminjaman['keterangan'];
            $buffer['status']      =  'Pending';
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
            ->where('status', PeminjamanStatus::Approved)
            ->orWhere('status', PeminjamanStatus::Borrowed)
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
            $peminjaman->status = PeminjamanStatus::Borrowed;
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
            $peminjaman->status = PeminjamanStatus::Returned;
            $peminjaman->save();

            $details = $peminjaman->detail;

            foreach ($details as $detail) {
                $barang = $detail->barang;
                $barang->save();
            }

            return response()->json(['message' => 'Status peminjaman telah diperbarui menjadi "dipinjam"!']);
        } else {
            return response()->json(['error' => 'Peminjaman gagal ditemukan!'], 404);
        }
    }

    public function fetch(Request $request) {
        return Peminjaman::where('id', $request['id'])->get();
    }

    public function fetchBarang(Request $request) {
        $data = Peminjaman::firstWhere('id', $request['id'])->barang;

        $data->setVisible(explode(',', $request['filter']));

        return $data;
    }
}
