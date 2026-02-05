<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Check if an index exists on a table
     */
    private function indexExists(string $table, string $indexName): bool
    {
        $indexes = DB::select("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$indexName]);
        return count($indexes) > 0;
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Index untuk tabel users - mempercepat query User::guru(), User::admin(), dll
        if (!$this->indexExists('users', 'users_role_index')) {
            Schema::table('users', function (Blueprint $table) {
                $table->index('role', 'users_role_index');
            });
        }

        // Index untuk tabel attendances - mempercepat filter berdasarkan tanggal dan status
        if (!$this->indexExists('attendances', 'attendances_date_index')) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->index('date', 'attendances_date_index');
            });
        }

        if (!$this->indexExists('attendances', 'attendances_status_index')) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->index('status', 'attendances_status_index');
            });
        }

        // Index untuk tabel scans - mempercepat query riwayat scan
        if (Schema::hasTable('scans')) {
            if (!$this->indexExists('scans', 'scans_scanned_at_index')) {
                Schema::table('scans', function (Blueprint $table) {
                    $table->index('scanned_at', 'scans_scanned_at_index');
                });
            }

            if (!$this->indexExists('scans', 'scans_item_scanned_index')) {
                Schema::table('scans', function (Blueprint $table) {
                    $table->index(['item_id', 'scanned_at'], 'scans_item_scanned_index');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if ($this->indexExists('users', 'users_role_index')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex('users_role_index');
            });
        }

        if ($this->indexExists('attendances', 'attendances_date_index')) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->dropIndex('attendances_date_index');
            });
        }

        if ($this->indexExists('attendances', 'attendances_status_index')) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->dropIndex('attendances_status_index');
            });
        }

        if (Schema::hasTable('scans')) {
            if ($this->indexExists('scans', 'scans_scanned_at_index')) {
                Schema::table('scans', function (Blueprint $table) {
                    $table->dropIndex('scans_scanned_at_index');
                });
            }

            if ($this->indexExists('scans', 'scans_item_scanned_index')) {
                Schema::table('scans', function (Blueprint $table) {
                    $table->dropIndex('scans_item_scanned_index');
                });
            }
        }
    }
};
