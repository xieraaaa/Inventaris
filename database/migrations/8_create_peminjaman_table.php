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
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_detail_peminjaman');

            $table->date('tgl_kembali');
            $table->date('tgl_pinjam');
            $table->string('status');
            $table->string('keterangan');
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Menghapus migrasinya
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
