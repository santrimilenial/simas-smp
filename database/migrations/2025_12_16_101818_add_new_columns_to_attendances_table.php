<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Cek apakah kolom sudah ada sebelum menambahkan
        if (!Schema::hasColumn('attendances', 'reason')) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->text('reason')->nullable()->after('status');
            });
        }
        
        if (!Schema::hasColumn('attendances', 'late_minutes')) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->integer('late_minutes')->default(0)->after('notes');
            });
        }
        
        // Update enum status dengan raw query (aman untuk semua versi MySQL)
        try {
            DB::statement("ALTER TABLE attendances MODIFY status ENUM('present', 'late', 'sick', 'permission', 'absent') NOT NULL DEFAULT 'present'");
        } catch (\Exception $e) {
            // Jika error, coba cara lain
            DB::statement("ALTER TABLE attendances CHANGE status status ENUM('present', 'late', 'sick', 'permission', 'absent') NOT NULL DEFAULT 'present'");
        }
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            if (Schema::hasColumn('attendances', 'reason')) {
                $table->dropColumn('reason');
            }
            if (Schema::hasColumn('attendances', 'late_minutes')) {
                $table->dropColumn('late_minutes');
            }
        });
        
        // Kembalikan enum ke semula
        try {
            DB::statement("ALTER TABLE attendances MODIFY status ENUM('present', 'late', 'absent', 'permission') NOT NULL DEFAULT 'present'");
        } catch (\Exception $e) {
            // Ignore error saat rollback
        }
    }
};