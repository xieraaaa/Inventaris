<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:resetdb';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::transaction(function() {
            DB::delete('DELETE FROM peminjaman');
            DB::statement('ALTER TABLE peminjaman AUTO_INCREMENT = 1');
            DB::statement('ALTER TABLE detail_peminjaman AUTO_INCREMENT = 1');
        });

        DB::transaction(function() {
            DB::delete('DELETE FROM users');
            DB::statement('ALTER TABLE users AUTO_INCREMENT = 1');
        });

        DB::transaction(function() {
            DB::delete('DELETE FROM peminjaman');
            DB::statement('ALTER TABLE peminjaman AUTO_INCREMENT = 1');
        });

        DB::transaction(function() {
            DB::delete('DELETE FROM pemindahan');
            DB::statement('ALTER TABLE pemindahan AUTO_INCREMENT = 1');
        });
    }
}
