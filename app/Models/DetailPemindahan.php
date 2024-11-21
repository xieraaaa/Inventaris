<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPemindahan extends Model
{
    protected $table = 'detail_pemindahan';

    protected $fillable = [
        'id_pemindahan',
        'id_barang',
        'jumlah'

    ];

    public function pemindahan(){
        return $this->belongsTo(Pemindahan::class, 'id_pemindahan');
    }
    public function barang(){
        return $this->belongsTo(Barang::class, 'id_barang');
    }
}
