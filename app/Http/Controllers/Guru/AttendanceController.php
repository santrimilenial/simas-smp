<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    public function checkIn(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'check_in_status' => 'required|in:present,permission,sick',
            'check_in_reason' => 'required_if:check_in_status,permission,sick|nullable|string|max:500',
            'teaching_hours' => 'nullable|integer|min:1|max:12',
            'notes' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ], [
            'check_in_status.required' => 'Status kehadiran harus dipilih.',
            'check_in_status.in' => 'Status kehadiran tidak valid.',
            'check_in_reason.required_if' => 'Keterangan harus diisi untuk status Izin atau Sakit.',
            'teaching_hours.integer' => 'Jumlah jam mengajar harus berupa angka.',
            'teaching_hours.min' => 'Jumlah jam mengajar minimal 1 jam.',
            'teaching_hours.max' => 'Jumlah jam mengajar maksimal 12 jam.',
        ]);

        $settings = AttendanceSetting::current();
        
        // Check if today is working day
        if (!$settings->isWorkingDay()) {
            return back()->with('error', 'Hari ini bukan hari kerja.');
        }

        // Check if it's time to check in (validasi waktu absen)
        $now = now();
        if (!$settings->canCheckIn($now)) {
            $workStartTime = Carbon::parse($settings->work_start);
            return back()->with('error', 'Belum waktunya absen. Absen masuk dimulai pada pukul ' . $settings->formatted_check_in_time . ' WIB.');
        }

        // Check if current time is after work end time (jam pulang)
        $workEndTime = Carbon::parse($settings->work_end);
        if ($now->format('H:i:s') > $workEndTime->format('H:i:s')) {
            return back()->with('error', 'Maaf, waktu untuk absen berangkat telah berlalu. Jam pulang sudah dimulai pada ' . $settings->formatted_check_out_time . '.');
        }

        // Check if already checked in today
        $existingAttendance = Attendance::getTodayAttendance(auth()->id());
        if ($existingAttendance && $existingAttendance->check_in_time) {
            return back()->with('error', 'Anda sudah melakukan absen berangkat hari ini.');
        }

        try {
            DB::beginTransaction();

            // Untuk izin/sakit, langsung simpan dengan status tersebut
            if (in_array($request->check_in_status, ['permission', 'sick'])) {
                
                $attendance = Attendance::create([
                    'user_id' => auth()->id(),
                    'date' => now()->toDateString(),
                    'check_in_time' => now(),
                    'status' => $request->check_in_status,
                    'check_in_status' => $request->check_in_status,
                    'check_in_reason' => $request->check_in_reason,
                    'notes' => $request->notes,
                    'late_minutes' => 0,
                    'teaching_hours' => null, // Tidak ada jam mengajar untuk izin/sakit
                    'check_in_latitude' => $request->latitude,
                    'check_in_longitude' => $request->longitude,
                ]);

                // Force update status in case create didn't save it
                $attendance->update(['status' => $request->check_in_status]);

                DB::commit();

                $statusLabel = $request->check_in_status === 'permission' ? 'Izin' : 'Sakit';
                return back()->with('success', "{$statusLabel} berhasil dicatat.");
            }

            // Untuk status "present", gunakan method checkIn untuk menentukan status akhir
            $attendance = Attendance::checkIn(
                auth()->id(),
                'present',
                null,
                $request->notes,
                $request->latitude,
                $request->longitude
            );

            // Simpan jam mengajar untuk status hadir
            $attendance->teaching_hours = $request->teaching_hours;
            $attendance->save();

            DB::commit();

            return back()->with('success', 'Absen berangkat berhasil dicatat!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log error untuk debug
            Log::error('Check-in failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return back()->with('error', 'Gagal melakukan absen berangkat. Silakan coba lagi atau hubungi administrator.');
        }
    }

    public function checkOut(Request $request)
    {
        $request->validate([
            'check_out_status' => 'required|in:present,early_leave',
            'check_out_reason' => 'required_if:check_out_status,early_leave|nullable|string|max:500',
            'notes' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ], [
            'check_out_status.required' => 'Status kepulangan harus dipilih.',
            'check_out_status.in' => 'Status kepulangan tidak valid.',
            'check_out_reason.required_if' => 'Alasan harus diisi untuk status Pulang Awal.',
        ]);

        // Check if already checked in today
        $attendance = Attendance::getTodayAttendance(auth()->id());
        
        if (!$attendance) {
            return back()->with('error', 'Anda belum melakukan absen berangkat hari ini.');
        }

        if ($attendance->check_out_time) {
            return back()->with('error', 'Anda sudah melakukan absen pulang hari ini.');
        }

        // Prevent checkout for permission/sick entries
        if (in_array($attendance->status, ['permission', 'sick'])) {
            return back()->with('error', 'Untuk status Izin atau Sakit, tidak perlu melakukan absen pulang.');
        }

        try {
            DB::beginTransaction();

            $attendance->update([
                'check_out_time' => now(),
                'check_out_status' => $request->check_out_status,
                'check_out_reason' => $request->check_out_reason,
                'notes' => $request->notes ?? $attendance->notes,
                'check_out_latitude' => $request->latitude,
                'check_out_longitude' => $request->longitude,
            ]);

            DB::commit();

            return back()->with('success', 'Absen pulang berhasil dicatat!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Check-out failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);
            
            return back()->with('error', 'Gagal melakukan absen pulang. Silakan coba lagi atau hubungi administrator.');
        }
    }

    public function history(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        $status = $request->get('status');

        $query = Attendance::with('user')
            ->byUser(auth()->id())
            ->byMonth($month, $year)
            ->orderBy('date', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        $attendances = $query->paginate(20);

        // Get summary for current month
        $summary = Attendance::getMonthlyStats(auth()->id(), $month, $year);

        // Get data for chart (last 6 months)
        $chartData = $this->getChartData(auth()->id());

        return view('guru.attendance.history', compact(
            'attendances',
            'summary',
            'chartData',
            'month',
            'year'
        ));
    }

    public function statistics(Request $request)
    {
        $year = $request->get('year', now()->year);
        $userId = auth()->id();

        // Monthly statistics for the year
        $monthlyStats = [];
        for ($month = 1; $month <= 12; $month++) {
            $stats = Attendance::getMonthlyStats($userId, $month, $year);
            $monthlyStats[] = [
                'month' => Carbon::create($year, $month, 1)->isoFormat('MMM'),
                'present' => $stats['present'],
                'late' => $stats['late'],
                'permission' => $stats['permission'],
                'sick' => $stats['sick'],
                'percentage' => round($stats['percentage'], 1),
            ];
        }

        // Year summary
        $yearAttendances = Attendance::byUser($userId)
            ->whereYear('date', $year)
            ->get();

        $yearSummary = [
            'present' => $yearAttendances->where('status', 'present')->count(),
            'late' => $yearAttendances->where('status', 'late')->count(),
            'permission' => $yearAttendances->where('status', 'permission')->count(),
            'sick' => $yearAttendances->where('status', 'sick')->count(),
            'total' => $yearAttendances->count(),
        ];

        return view('guru.attendance.statistics', compact(
            'monthlyStats',
            'yearSummary',
            'year'
        ));
    }

    private function getChartData($userId)
    {
        $labels = [];
        $presentData = [];
        $lateData = [];
        $permissionData = [];
        $sickData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->month;
            $year = $date->year;

            $stats = Attendance::getMonthlyStats($userId, $month, $year);

            $labels[] = $date->isoFormat('MMM YYYY');
            $presentData[] = $stats['present'];
            $lateData[] = $stats['late'];
            $permissionData[] = $stats['permission'];
            $sickData[] = $stats['sick'];
        }

        return [
            'labels' => $labels,
            'present' => $presentData,
            'late' => $lateData,
            'permission' => $permissionData,
            'sick' => $sickData,
        ];
    }
}