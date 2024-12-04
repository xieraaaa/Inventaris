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
    protected static function booted()
    {
        static::creating(function ($unit) {
            $unit->tanggal_inventaris = $unit->tanggal_inventaris ?? now();
        });
    }

}
