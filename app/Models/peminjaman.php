<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $fillable = [
        'id_barang',
        'id_user',
        'tgl_pinjam',
        'tgl_kembali',
        'keterangan',
        'status',
    ];

    public function Barang() {
        return $this->belongsTo(barang::class, 'id_barang');
    }

    public function user() {
        return $this->belongsTo(User::class, 'id');
    }
    
}
