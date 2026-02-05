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
        Schema::create('tujuan_pembelajarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID Guru
            $table->string('subject'); // Mata Pelajaran
            $table->text('description'); // Deskripsi TP
            $table->boolean('is_active')->default(true); // Status aktif
            $table->timestamps();
            
            // Index untuk query cepat
            $table->index(['user_id', 'subject', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tujuan_pembelajarans');
    }
};
