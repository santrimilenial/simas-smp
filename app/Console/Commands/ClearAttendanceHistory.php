<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use Illuminate\Console\Command;

class ClearAttendanceHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:clear-history {--confirm : Skip confirmation prompt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hapus semua riwayat absensi dari database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = Attendance::count();

        if ($count === 0) {
            $this->info('📋 Tidak ada data absensi yang perlu dihapus.');
            return Command::SUCCESS;
        }

        $this->warn("⚠️  Peringatan: Anda akan menghapus {$count} record absensi!");
        $this->warn('⚠️  Tindakan ini TIDAK DAPAT DIBATALKAN!');

        // Jika flag --confirm tidak diberikan, minta konfirmasi
        if (!$this->option('confirm')) {
            if (!$this->confirm('Apakah Anda yakin ingin menghapus semua riwayat absensi?')) {
                $this->info('❌ Pembatalan. Tidak ada data yang dihapus.');
                return Command::SUCCESS;
            }
        }

        // Hapus semua data
        Attendance::truncate();

        $this->info("✅ Berhasil menghapus {$count} record absensi!");
        $this->info('📋 Database absensi telah dikosongkan.');

        return Command::SUCCESS;
    }
}
