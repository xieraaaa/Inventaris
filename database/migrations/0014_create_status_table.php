<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration {
    public function up()
    {
        Schema::create('status', function (Blueprint $table) {
            $table->id();
            $table->string('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('status');
    }
};
