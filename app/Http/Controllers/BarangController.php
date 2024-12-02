<?php

namespace App\Http\Controllers;

use App\Models\barang;
use App\Models\kategori;
use App\Models\merek;
use App\Models\Unit;
use Illuminate\Http\Request;
use Milon\Barcode\DNS1D;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

use App\Imports\ExcelData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getDatatables();
        }

        $kategoris = kategori::all();
        $units = Unit::all();
        $mereks = Merek::all();
        $barang = Barang::first();
        $kondisiLabel = $barang ? ($barang->kondisi == 1 ? 'Baik' : 'Rusak') : 'N/A';

        return view('content.barang.admin', compact('kategoris', 'units', 'mereks', 'kondisiLabel'));
    }

    /**
     * Mengambil data untuk AJAX Datatable
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function getDatatables()
    {
        $rootData   = [];
        $barangData = Barang::with(['unit', 'kategori', 'merek', 'unitBarang'])->get();

        foreach ($barangData as $datum) {
            $tmpDatum = [];

            $tmpDatum['id']          = $datum['id'];
            $tmpDatum['nama_barang'] = $datum['nama_barang'];
            $tmpDatum['unit']        = $datum->unit['unit'];
            $tmpDatum['merek']       = $datum->merek['merek'];
            $tmpDatum['kategori']    = $datum->kategori['kategori'];
            $tmpDatum['unitBarang']  = $datum->unitBarang;
            $tmpDatum['jumlah']      = count($tmpDatum['unitBarang']);

            array_push($rootData, $tmpDatum);
        }

        return $rootData;
    }

    /**
     * Mengembalikan data barang melalui format JSON berikut:
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function get()
    {
        $data = [];
        $rawData = Barang::withCount(['unitBarang' => function(Builder $query) {
            $query->where('kondisi', 'Tersedia');
        }])->get();

        foreach ($rawData as $datum) {
            $tmpDatum = [];

            $tmpDatum['id']          = $datum['id'];
            $tmpDatum['nama_barang'] = $datum['nama_barang'];
            $tmpDatum['jumlah']      = $datum['unit_barang_count'];

            array_push($data, $tmpDatum);
        }

        return $data;
    }

    public function filtered_get(Request $request) {
        Log::info($request['query']);

        $data = Barang::where('nama_barang', 'like', $request['query'] . '%')->get();

        Log::info($data);

        return $data;
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
            'kode_barang' => 'required',
            'nama_barang' => 'required',
            'id_kategori' => 'required',
            'id_unit' => 'required',
            'id_merek' => 'required',
            'jumlah' => 'required',
            'kondisi' => 'required',
            'keterangan' => 'required',

        ]);

        $barang = barang::updateOrCreate(
            [
                'id' => $barangId
            ],
            [
                'kode_barang' => $request->kode_barang,
                'nama_barang' => $request->nama_barang,
                'id_kategori' => $request->id_kategori,
                'id_unit' => $request->id_unit,
                'id_merek' => $request->id_merek,
                'jumlah' => $request->jumlah,
                'kondisi' => $request->kondisi,
                'keterangan' => $request->keterangan,
            ]
        );

        $barcodeBase64 = (new DNS1D())->getBarcodePNG($barang->kode_barang, 'C39', 1.5, 50);

        // Menyimpan base64 string sebagai file image
        $barcodePath = 'barcodes/' . $barang->kode_barang . '.png';
        Storage::disk('public')->put($barcodePath, base64_decode($barcodeBase64));

        return response()->json([
            'barang' => $barang,
            'barcode_url' => asset('storage/' . $barcodePath)
        ]);

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
        $barang  = barang::where($where)->first();

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

        $file      = $request->file('data_excel');
        $nama_file = $file->hashName();
        $path      = $file->store('app/public/excel');

        $import = Excel::import(new ExcelData, storage_path('app/public/excel/' . $nama_file));

        if ($import) {
            // Ambil semua data barang yang diimpor
            $barangs = Barang::all();
            $generator = new DNS1D();

            foreach ($barangs as $barang) {
                if ($barang->kode_barang) {
                    $barcodeBase64 = $generator->getBarcodePNG($barang->kode_barang, 'C39', 1.5, 50);
                    $barcodePath = 'barcodes/' . $barang->kode_barang . '.png';
                    Storage::put($barcodePath, base64_decode($barcodeBase64));
                }
            }

            Storage::delete($path);
            return redirect()->route('barang')->with(['success' => 'Data Berhasil Diimport dan Barcode Dihasilkan!']);
        } else {
        return redirect()->route('barang')->with(['error' => 'Data Gagal Diimport!']);
        }
    }
}
