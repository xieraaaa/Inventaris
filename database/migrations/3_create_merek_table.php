<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasinya
     */
    public function up(): void
    {
        Schema::create('merek', function (Blueprint $table) {
            $table->id();
            $table->string('merek')->unique();
        });
    }

    /**
     * Hapus migrasinya
     */
    public function down(): void
    {
        Schema::dropIfExists('merek');
    }
};
