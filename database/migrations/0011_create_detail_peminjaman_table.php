<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migrasinya
     */
    public function up(): void
    {
        Schema::create('detail_peminjaman', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('id_peminjaman');
			$table->unsignedBigInteger('id_barang');

			$table->unsignedInteger('jumlah');

			$table->foreign('id_peminjaman')->references('id')->on('peminjaman')->onDelete('cascade');
			$table->foreign('id_barang')->references('id')->on('barang')->onDelete('cascade');
		});
    }

    /**
     * Menghapus migrasinya
     */
    public function down(): void
    {
        Schema::dropIfExists('detail-peminjaman');
    }
};
