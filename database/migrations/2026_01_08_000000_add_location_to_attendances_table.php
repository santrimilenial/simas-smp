<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            // Lokasi saat check-in
            if (!Schema::hasColumn('attendances', 'check_in_latitude')) {
                $table->decimal('check_in_latitude', 10, 8)->nullable()->after('check_in_time');
            }
            if (!Schema::hasColumn('attendances', 'check_in_longitude')) {
                $table->decimal('check_in_longitude', 11, 8)->nullable()->after('check_in_latitude');
            }
            
            // Lokasi saat check-out
            if (!Schema::hasColumn('attendances', 'check_out_latitude')) {
                $table->decimal('check_out_latitude', 10, 8)->nullable()->after('check_out_time');
            }
            if (!Schema::hasColumn('attendances', 'check_out_longitude')) {
                $table->decimal('check_out_longitude', 11, 8)->nullable()->after('check_out_latitude');
            }
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn([
                'check_in_latitude',
                'check_in_longitude',
                'check_out_latitude',
                'check_out_longitude',
            ]);
        });
    }
};
