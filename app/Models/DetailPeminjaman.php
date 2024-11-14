<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPeminjaman extends Model {
    protected $table      = 'detail-peminjaman';
    public    $timestamps = false;
    protected $fillable   = [
        'id_peminjaman',
        'id_barang',
        'jumlah'
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman');
    }
}
