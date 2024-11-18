<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $fillable = [
        'id_user',
        'tgl_pinjam',
        'tgl_kembali',
        'keterangan',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function detail()
    {
        return $this->hasMany(DetailPeminjaman::class, 'id_peminjaman');
    }

    public function barang()
    {
        return $this->belongsToMany(Barang::class, 'detail-peminjaman', 'id_peminjaman', 'id_barang');
    }
}
