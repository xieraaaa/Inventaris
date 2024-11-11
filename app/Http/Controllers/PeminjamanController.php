<?php

namespace App\Http\Controllers;

use App\DataTables\PeminjamanDataTable;

class PeminjamanController extends Controller
{
    public function index(PeminjamanDataTable $dataTable)
    {
        return $dataTable->render('content.peminjaman.index');
    }

}
