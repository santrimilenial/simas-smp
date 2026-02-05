<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teaching_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('subject'); // Mata pelajaran
            $table->string('class'); // Kelas (misal: 7A, 8B, 9C)
            $table->integer('meeting_number')->default(1); // Pertemuan ke berapa
            $table->text('tp'); // Tujuan Pembelajaran
            $table->text('material'); // Materi yang diajarkan
            $table->string('time_slot'); // Jam ke berapa (misal: 1-2, 3-4)
            $table->text('notes')->nullable(); // Catatan tambahan
            $table->date('log_date'); // Tanggal mengajar
            $table->timestamps();

            // Index untuk performa query
            $table->index(['user_id', 'log_date']);
            $table->index(['class', 'subject']); // Index untuk filter kelas & mapel
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teaching_logs');
    }
};