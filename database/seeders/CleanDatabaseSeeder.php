<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

class CleanDatabaseSeeder extends Seeder
{
    /**
     * Clean database for deployment - keep only admin and bendahara accounts
     */
    public function run(): void
    {
        $this->command->info('🧹 Membersihkan database untuk deployment...');

        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();

        // Get admin and bendahara user IDs to keep
        $keepUserIds = User::whereIn('role', ['admin', 'bendahara'])->pluck('id')->toArray();
        
        $this->command->info('📌 Menyimpan ' . count($keepUserIds) . ' akun (admin & bendahara)');

        // Tables to truncate completely (no user dependency)
        $tablesToTruncate = [
            'academic_years',
            'attendance_settings',
            'classes',
            'items',
            'subjects',
            'tujuan_pembelajarans',
            'password_reset_logs',
            'password_reset_tokens',
            'sessions',
            'cache',
            'cache_locks',
            'jobs',
            'job_batches',
            'failed_jobs',
        ];

        // Tables to clean but keep records for admin/bendahara
        $userRelatedTables = [
            'teaching_logs',
            'attendances',
            'scans',
            'slip_gaji',
        ];

        // Truncate tables without user dependency
        foreach ($tablesToTruncate as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
                $this->command->info("✅ Tabel '{$table}' dibersihkan");
            }
        }

        // Clean user-related tables (remove non-admin/bendahara records)
        foreach ($userRelatedTables as $table) {
            if (Schema::hasTable($table)) {
                $deleted = DB::table($table)->whereNotIn('user_id', $keepUserIds)->delete();
                $this->command->info("✅ Tabel '{$table}' dibersihkan ({$deleted} record dihapus)");
            }
        }

        // Delete users except admin and bendahara
        $deletedUsers = User::whereNotIn('role', ['admin', 'bendahara'])->delete();
        $this->command->info("✅ {$deletedUsers} user (guru/staff) dihapus");

        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();

        // Show remaining users
        $this->command->newLine();
        $this->command->info('📋 Akun yang tersisa:');
        User::all()->each(function ($user) {
            $this->command->line("   - {$user->name} ({$user->email}) - {$user->role}");
        });

        $this->command->newLine();
        $this->command->info('✨ Database siap untuk deployment!');
    }
}
