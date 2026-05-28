<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE items MODIFY COLUMN `condition` ENUM('baik', 'rusak ringan', 'rusak berat') DEFAULT 'baik'");

        // Migrate old values to new ones
        DB::table('items')->where('condition', 'rusak')->update(['condition' => 'rusak ringan']);
        DB::table('items')->where('condition', 'perlu_perbaikan')->update(['condition' => 'rusak ringan']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE items MODIFY COLUMN `condition` ENUM('baik', 'rusak', 'perlu_perbaikan') DEFAULT 'baik'");
    }
};
