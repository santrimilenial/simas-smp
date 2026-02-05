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
        Schema::table('attendances', function (Blueprint $table) {
            // Kolom untuk check-in status dan reason
            if (!Schema::hasColumn('attendances', 'check_in_status')) {
                $table->enum('check_in_status', ['present', 'permission', 'sick'])->nullable()->after('status');
            }
            if (!Schema::hasColumn('attendances', 'check_in_reason')) {
                $table->text('check_in_reason')->nullable()->after('check_in_status');
            }
            
            // Kolom untuk check-out status dan reason
            if (!Schema::hasColumn('attendances', 'check_out_status')) {
                $table->enum('check_out_status', ['present', 'early_leave'])->nullable()->after('check_out_time');
            }
            if (!Schema::hasColumn('attendances', 'check_out_reason')) {
                $table->text('check_out_reason')->nullable()->after('check_out_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            if (Schema::hasColumn('attendances', 'check_in_status')) {
                $table->dropColumn('check_in_status');
            }
            if (Schema::hasColumn('attendances', 'check_in_reason')) {
                $table->dropColumn('check_in_reason');
            }
            if (Schema::hasColumn('attendances', 'check_out_status')) {
                $table->dropColumn('check_out_status');
            }
            if (Schema::hasColumn('attendances', 'check_out_reason')) {
                $table->dropColumn('check_out_reason');
            }
        });
    }
};
