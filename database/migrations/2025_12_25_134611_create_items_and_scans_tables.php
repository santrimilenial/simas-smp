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
        // Tabel untuk menyimpan data barang/properti sekolah
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama barang
            $table->string('code')->unique(); // Kode barang (untuk barcode)
            $table->string('category')->nullable(); // Kategori (Elektronik, Furniture, dll)
            $table->text('description')->nullable(); // Deskripsi barang
            $table->string('location')->nullable(); // Lokasi barang di sekolah
            $table->enum('condition', ['baik', 'rusak', 'perlu_perbaikan'])->default('baik'); // Kondisi barang
            $table->integer('quantity')->default(1); // Jumlah barang
            $table->decimal('price', 15, 2)->nullable(); // Harga barang
            $table->date('purchase_date')->nullable(); // Tanggal pembelian
            $table->string('barcode_path')->nullable(); // Path file barcode image
            $table->timestamps();
            $table->softDeletes(); // Soft delete untuk riwayat
        });

        // Tabel untuk menyimpan riwayat scan barcode oleh staff
        Schema::create('scans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade'); // Barang yang di-scan
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Staff yang melakukan scan
            $table->timestamp('scanned_at'); // Waktu scan
            $table->string('scan_type')->default('manual'); // manual atau camera
            $table->string('location')->nullable(); // Lokasi saat scan (opsional)
            $table->text('notes')->nullable(); // Catatan (opsional)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scans');
        Schema::dropIfExists('items');
    }
};
