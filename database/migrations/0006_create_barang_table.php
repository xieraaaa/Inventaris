<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
		Schema::create('barang', function(Blueprint $table) {
			$table->id();

			$table->string('nama_barang', 50)->unique();

			$table->unsignedBigInteger('id_kategori');
			$table->unsignedBigInteger('id_unit');
			$table->unsignedBigInteger('id_merek');

			$table->timestamps();

			$table->foreign('id_kategori')->references('id')->on('kategori')->onDelete('cascade');
			$table->foreign('id_unit')->references('id')->on('unit')->onDelete('cascade');
			$table->foreign('id_merek')->references('id')->on('merek')->onDelete('cascade');
		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
