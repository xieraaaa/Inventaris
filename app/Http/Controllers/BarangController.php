<?php

namespace App\Http\Controllers;

use App\Models\{
    Barang,
    DetailPeminjaman,
    Kategori,
    Merek,
    Peminjaman,
    Unit
};
use Illuminate\Http\Request;
use Milon\Barcode\DNS1D;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

use App\Imports\ExcelData;
use App\Models\UnitBarang;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class BarangController extends Controller
{
        public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getDatatables($request);  // Call the getDatatables method for AJAX requests
        }

        // Fetch necessary data for the view
        $kategoris = kategori::all();  // Fetch all categories
        $units = Unit::all();          // Fetch all units
        $mereks = Merek::all();        // Fetch all brands
        $barang = Barang::first();     // Fetch the first barang (item)
        $kondisiLabel = $barang ? ($barang->kondisi == 1 ? 'Baik' : 'Rusak') : 'N/A';  // Label for condition

        // Return the view with the fetched data
        return view('content.barang.admin', compact('kategoris', 'units', 'mereks', 'kondisiLabel'));
    }

    /**
     * Mengambil data untuk AJAX Datatable
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function getDatatables(Request $request)
    {
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

    /**
     * Mengembalikan data barang melalui format JSON berikut:
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function get()
{
    $data = [];
    $rawData = Barang::withCount([
        'unitBarang' => function (Builder $query) {
            $query->where('kondisi', 'Tersedia');
        }
    ])->paginate(20);

    foreach ($rawData as $datum) {
        $tmpDatum = [];
        $unitBarangCount = $datum['unit_barang_count'];

        // Hitung jumlah barang yang sedang dipinjam (status 'Borrowed')
        $jumlahDipinjam = DetailPeminjaman::whereHas('peminjaman', function ($query) {
            $query->where('status', 4); // Hanya menghitung barang dengan status Borrowed
        })->where('id_barang', $datum['id'])->sum('jumlah');

        // Kurangi jumlah barang yang dipinjam dari stok tersedia
        $unitBarangCount -= $jumlahDipinjam;

        if ($unitBarangCount <= 0) {
            continue;
        }

        $tmpDatum['id'] = $datum['id'];
        $tmpDatum['nama_barang'] = $datum['nama_barang'];
        $tmpDatum['jumlah'] = $unitBarangCount;

        array_push($data, $tmpDatum);
    }

    return $data;
}

public function filtered_get(Request $request)
{
    Log::info($request['query']);
    $raw_data = [];
    $data = Barang::with('unitBarang')->where('nama_barang', 'like', $request['query'] . '%')->get();

    foreach ($data as $datum) {
        $jumlah = count($datum['unitBarang']);

        // Hitung jumlah barang yang sedang dipinjam (status Borrowed)
        $peminjaman = Peminjaman::with('detail')->where('status', 4)->get();
        $jumlahKurang = (function() use ($peminjaman, $datum) {
            $tmp = 0;
            foreach ($peminjaman as $p) {
                $tmp += DetailPeminjaman::where('id_peminjaman', $p['id'])
                    ->where('id_barang', $datum['id'])
                    ->sum('jumlah');
            }
            return $tmp;
        })();

        // Kurangi jumlah barang yang dipinjam dari stok tersedia
        $jumlah -= $jumlahKurang;

        if ($jumlah <= 0) {
            continue;
        }

        $tmpDatum = [
            'id' => $datum['id'],
            'nama_barang' => $datum['nama_barang'],
            'jumlah' => $jumlah,
        ];

        array_push($raw_data, $tmpDatum);
    }

    Log::info($data);
    return $raw_data;
}


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $barangId = $request->id;

        $request->validate([
            
            'nama_barang' => 'required',
            'id_kategori' => 'required',
            'id_unit' => 'required',
            'id_merek' => 'required',

        ]);

        $barang = barang::updateOrCreate(
            [
                'id' => $barangId
            ],
            [
                
                'nama_barang' => $request->nama_barang,
                'id_kategori' => $request->id_kategori,
                'id_unit' => $request->id_unit,
                'id_merek' => $request->id_merek,

            ]
        );

        return Response()->json($barang);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $where = array('id' => $request->id);
        $barang = barang::where($where)->first();

        return Response()->json($barang);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // Ambil data barang berdasarkan ID yang diberikan
        $barang = barang::find($request->id);

        // Pastikan data barang ada sebelum menghapusnya
        if (!$barang) {
            return response()->json([
                "status" => "failed",
                "msg" => "Barang tidak ditemukan!"
            ], 404);
        }

        // Hapus barcode jika ada
        $barcodePath = 'barcodes/' . $barang->kode_barang . '.png'; // Path ke barcode
        if (Storage::disk('public')->exists($barcodePath)) {
            Storage::disk('public')->delete($barcodePath); // Menghapus barcode
        }

        // Hapus barang
        $barang->delete();

        return response()->json([
            "status" => "success",
            "msg" => "Barang berhasil dihapus"
        ], 201);
    }

    public function import(Request $request)
    {
        $request->validate([
            'data_excel' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('data_excel');
        $nama_file = $file->hashName();
        $path = $file->store('app/public/excel');

        $import = Excel::import(new ExcelData, storage_path('app/public/excel/' . $nama_file));

        if ($import) {
            // Ambil semua data barang yang diimpor
            $barangs = Barang::all();
            $generator = new DNS1D();

            foreach ($barangs as $barang) {
                if ($barang->kode_inventaris) {
                    $barcodeBase64 = $generator->getBarcodePNG($barang->kode_inventaris, 'C39', 1.5, 50);
                    $barcodePath = 'barcodes/' . $barang->kode_inventaris . '.png';
                    Storage::put($barcodePath, base64_decode($barcodeBase64));
                }
            }

            Storage::delete($path);
            return redirect()->route('barang')->with(['success' => 'Data Berhasil Diimport dan Barcode Dihasilkan!']);
        } else {
            return redirect()->route('barang')->with(['error' => 'Data Gagal Diimport!']);
        }
    }

    public function unit(Request $request)
    {
        // Validasi input
        $request->validate([
            'id_barang' => 'required|exists:barang,id',
            'kode_inventaris' => 'required|string',
            'lokasi' => 'required|string',
            'kondisi' => 'required',
        ]);

        try {
            // Proses penyimpanan data unit
            $unit = UnitBarang::updateOrCreate(
                [
                    'id' => $request->id
                ],[
                'id_barang' => $request->id_barang,
                'kode_inventaris' => $request->kode_inventaris,
                'lokasi' => $request->lokasi,
                'kondisi' => $request->kondisi,
                'tanggal_inventaris' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Unit berhasil ditambahkan!',
                'unit' => $unit,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function get_unit_barang(int $id, string $fields)
    {
        $return_data = [];
        $raw_data = UnitBarang::where('id_barang', $id)->get();
        $valid_fields = array_merge(['id'], app(UnitBarang::class)->getFillable());
        $fields_deserialized = explode(',', $fields);
        $fields_deserialized_count = count($fields_deserialized);

        if (!$fields_deserialized_count) {
            return response(null, 400);
        }

        if ($fields_deserialized_count === 1) {
            $field = $fields_deserialized[0];

            if (!in_array($field, $valid_fields)) {
                return response(null, 400);
            }

            $raw_data->setVisible([$field]);

            $raw_data_count = count($raw_data);
            for ($idx = 0; $idx < $raw_data_count; ++$idx) {
                array_push($return_data, $raw_data[$idx][$field]);
            }

            return $return_data;
        }

        $raw_data->setVisible(['']);

        for ($idx = 0; $idx < $fields_deserialized_count; ++$idx) {
            if (!in_array($fields_deserialized[$idx], $valid_fields)) {
                return response(null, 400);
            }

            $raw_data->makeVisible($fields_deserialized[$idx]);
        }

        return $raw_data;
    }

    public function editUnit(Request $request, $id)
{
    $where = array('id' => $request->id);
    $unit = UnitBarang::where($where)->first();

    return Response()->json($unit);
}


    
    // Delete unit
    public function destroyUnit(Request $request)
    {
        $unit_barang = UnitBarang::find($request->id);

        if ($unit_barang) {
            $unit_barang->delete();

            return response()->json([
                'success' => true,
                'message' => 'Unit barang deleted successfully',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unit not found',
            ], 404);
        }
    }
}

