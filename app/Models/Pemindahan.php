<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemindahan extends Model
{
    protected $table = 'pemindahan';

    protected $fillable = [ 
        'tanggal',
        'asal',
        'tujuan',
        'deskripsi',
    ];

    public function detail()
    {
        return $this->hasMany(DetailPemindahan::class, 'id_pemindahan');
    }
}
