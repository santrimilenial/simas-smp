<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\TeachingLog;
use App\Models\Attendance;
use App\Models\Subject;
use App\Models\AcademicYear;
use App\Models\ClassModel;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        
        $totalJurnal = TeachingLog::where('user_id', $userId)->count();
        
        $jurnalBulanIni = TeachingLog::where('user_id', $userId)
            ->whereMonth('log_date', Carbon::now()->month)
            ->whereYear('log_date', Carbon::now()->year)
            ->count();
        
        $jurnalMingguIni = TeachingLog::where('user_id', $userId)
            ->whereBetween('log_date', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])
            ->count();
        
        $jurnalHariIni = TeachingLog::where('user_id', $userId)
            ->whereDate('log_date', Carbon::today())
            ->count();

        // Jurnal terbaru
        $recentLogs = TeachingLog::where('user_id', $userId)
            ->recent()
            ->limit(10)
            ->get();

        // Statistik per mata pelajaran bulan ini
        $subjectStats = TeachingLog::where('user_id', $userId)
            ->whereMonth('log_date', Carbon::now()->month)
            ->whereYear('log_date', Carbon::now()->year)
            ->selectRaw('subject, COUNT(*) as total')
            ->groupBy('subject')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // Riwayat absensi 7 hari terakhir
        $attendanceHistory = Attendance::where('user_id', $userId)
            ->whereBetween('date', [
                Carbon::now()->subDays(6)->startOfDay(),
                Carbon::now()->endOfDay()
            ])
            ->orderBy('date', 'desc')
            ->get()
            ->map(function($attendance) {
                $attendance->status_label = match($attendance->status) {
                    'present' => 'Hadir',
                    'late' => 'Terlambat',
                    'absent' => 'Tidak Hadir',
                    'permit' => 'Izin',
                    'sick' => 'Sakit',
                    default => ucfirst($attendance->status)
                };
                $attendance->formatted_check_in = $attendance->check_in_time 
                    ? Carbon::parse($attendance->check_in_time)->format('H:i') 
                    : null;
                $attendance->formatted_check_out = $attendance->check_out_time 
                    ? Carbon::parse($attendance->check_out_time)->format('H:i') 
                    : null;
                return $attendance;
            });

        // Jadwal mengajar hari ini (data dummy atau dari model Schedule jika ada)
        // Untuk sekarang kita bisa ambil dari jurnal hari ini atau set kosong
        $todaySchedule = collect(); // Bisa dikembangkan jika ada model Schedule

        // Data untuk modal create jurnal
        $subjects = Subject::where('user_id', $userId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();
        $classes = ClassModel::getActiveClassNames();

        return view('guru.dashboard', compact(
            'totalJurnal',
            'jurnalBulanIni',
            'jurnalMingguIni',
            'jurnalHariIni',
            'recentLogs',
            'subjectStats',
            'attendanceHistory',
            'todaySchedule',
            'subjects',
            'academicYears',
            'activeAcademicYear',
            'classes'
        ));
    }
}