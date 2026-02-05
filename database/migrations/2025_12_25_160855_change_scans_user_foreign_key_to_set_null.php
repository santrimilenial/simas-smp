<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop foreign key constraint lama
        Schema::table('scans', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        // Ubah kolom user_id menjadi nullable
        Schema::table('scans', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
        });

        // Tambah foreign key baru dengan onDelete SET NULL
        Schema::table('scans', function (Blueprint $table) {
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke cascade
        Schema::table('scans', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('scans', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
        });

        Schema::table('scans', function (Blueprint $table) {
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }
};
