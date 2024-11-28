<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration {
    public function up()
    {
        Schema::create('unit_barang', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_barang');
            $table->string('kode_inventaris', 55)->unique();
            $table->string('lokasi', 255);
            $table->string('kondisi', 255);
            $table->date('tanggal_inventaris');
            $table->timestamps();

            $table->foreign('id_barang')->references('id')->on('barang')->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('unit_barang');
    }
};
