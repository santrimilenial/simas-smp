<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->date('record_date');
            $table->enum('type', ['income', 'expense']); // pemasukan / pengeluaran
            $table->string('category'); // kategori (SPP, Gaji, Operasional, dll)
            $table->text('description');
            $table->decimal('amount', 15, 2);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['record_date', 'type']);
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_records');
    }
};
