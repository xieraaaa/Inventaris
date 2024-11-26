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
        Schema::create('detail_pemindahan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pemindahan');
            $table->unsignedBigInteger('id_barang');
            $table->integer('jumlah');
            $table->timestamps();

            $table->foreign('id_pemindahan')->references('id')->on('pemindahan')->onDelete('cascade');
            $table->foreign('id_barang')->references('id')->on('barang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pemindahan');
    }
};
