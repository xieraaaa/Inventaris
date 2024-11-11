<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class peminjaman extends Model
{
    protected $table = 'peminjamen';

    protected $fillable = [
        'id_barang',
        'id_user',
        'tanggal_pinjam',
        'tanggal_kembali',
        'keterangan',
        'status',
    ];
    
}
