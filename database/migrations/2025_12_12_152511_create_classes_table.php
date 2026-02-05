<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Nama kelas (7A, 8B, dll)
            $table->string('grade_level'); // Tingkat (7, 8, 9, 10, 11, 12)
            $table->string('class_group')->nullable(); // Grup kelas (A, B, C, IPA 1, IPS 2, dll)
            $table->integer('student_count')->default(0); // Jumlah siswa
            $table->boolean('is_active')->default(true); // Status aktif
            $table->integer('order')->default(0); // Urutan tampilan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};