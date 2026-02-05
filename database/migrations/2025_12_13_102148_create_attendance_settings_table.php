<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_settings', function (Blueprint $table) {
            $table->id();
            $table->time('late_time')->default('07:30:00'); // Batas waktu terlambat
            $table->time('work_start')->default('07:00:00'); // Jam kerja mulai
            $table->time('work_end')->default('16:00:00'); // Jam kerja selesai
            $table->boolean('allow_early_checkin')->default(true); // Boleh absen lebih awal
            $table->integer('grace_period')->default(0); // Grace period dalam menit
            $table->timestamps();
        });

        // Insert default settings
        DB::table('attendance_settings')->insert([
            'late_time' => '07:30:00',
            'work_start' => '07:00:00',
            'work_end' => '16:00:00',
            'allow_early_checkin' => true,
            'grace_period' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_settings');
    }
};