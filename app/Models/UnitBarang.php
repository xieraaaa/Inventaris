<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{
    Factories\HasFactory,
    Model
};

class UnitBarang extends Model {
    use HasFactory;

    protected $table = 'unit_barang';

    protected $fillable = [
        'id_barang',
        'kode_inventaris',
        'lokasi',
        'kondisi',
        'tanggal_inventaris'
    ];


}
