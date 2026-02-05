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
        Schema::create('slip_gaji', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Guru
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // Bendahara
            $table->integer('month'); // 1-12
            $table->integer('year');
            $table->integer('total_teaching_hours')->default(0); // Total jam mengajar
            $table->decimal('rate_per_hour', 12, 2)->default(10000); // Rate per jam (default 10000)
            $table->decimal('total_amount', 15, 2)->default(0); // Total gaji
            $table->string('status')->default('draft'); // draft, approved, paid
            $table->text('notes')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            // Unique constraint: one slip per teacher per month
            $table->unique(['user_id', 'month', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slip_gaji');
    }
};
