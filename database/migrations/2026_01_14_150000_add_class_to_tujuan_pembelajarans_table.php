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
        Schema::table('tujuan_pembelajarans', function (Blueprint $table) {
            $table->string('class')->nullable()->after('subject'); // Kelas untuk TP
            
            // Update index untuk include class
            $table->index(['user_id', 'subject', 'class', 'is_active'], 'tp_user_subject_class_active_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tujuan_pembelajarans', function (Blueprint $table) {
            $table->dropIndex('tp_user_subject_class_active_idx');
            $table->dropColumn('class');
        });
    }
};
