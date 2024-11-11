<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $fillable = [
        'id_barang',
        'tanggal_peminjaman',
        'tanggal_kembali',
        'keterangan',
        'status',
    ];
    
}
