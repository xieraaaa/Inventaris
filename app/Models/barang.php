<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{
    Model,
    Factories\HasFactory   
};

class Barang extends Model
{
    use HasFactory;
    
    protected $table = 'barang';

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'id_kategori',
        'id_unit',
        'id_merek',
        'jumlah',
        'kondisi',
        'keterangan'
        
    ];
   
    public function kategori() {
        return $this->belongsTo(kategori::class, 'id_kategori');
    }

    public function unit() {
        return $this->belongsTo(Unit::class, 'id_unit');
    }

    public function merek() {
        return $this->belongsTo(merek::class, 'id_merek');
    }
}
