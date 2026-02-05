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
        Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: 2025/2026
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            
            $table->unique('name');
        });
        
        // Tambah kolom academic_year_id ke teaching_logs
        Schema::table('teaching_logs', function (Blueprint $table) {
            $table->foreignId('academic_year_id')->nullable()->after('user_id')->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teaching_logs', function (Blueprint $table) {
            $table->dropForeign(['academic_year_id']);
            $table->dropColumn('academic_year_id');
        });
        
        Schema::dropIfExists('academic_years');
    }
};
