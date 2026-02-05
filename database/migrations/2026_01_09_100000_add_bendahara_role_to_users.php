<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adding 'bendahara' role to users table
     */
    public function up(): void
    {
        // Since MySQL doesn't support easily adding enum values, 
        // we'll change the column to string type to support more roles
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->default('guru')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to previous state if needed
    }
};
