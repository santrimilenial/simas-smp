<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceSetting;
use App\Models\User;
use App\Exports\MonthlyAttendanceExport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function daily(Request $request)
    {
        $date = $request->get('date', now()->toDateString());
        $status = $request->get('status');
        $search = $request->get('search');

        $query = Attendance::with('user')
            ->whereDate('date', $date)
            ->orderBy('check_in_time');

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            // Search by user_id if it's a valid ID
            if (is_numeric($search)) {
                $query->where('user_id', $search);
            } else {
                // Fallback for old search format (if manually entered)
                $query->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('niy', 'like', "%{$search}%");
                });
            }
        }

        $attendances = $query->paginate(20)->withQueryString();

        // Summary
        $summary = [
            'present' => Attendance::whereDate('date', $date)->where('status', 'present')->count(),
            'late' => Attendance::whereDate('date', $date)->where('status', 'late')->count(),
            'permission' => Attendance::whereDate('date', $date)->where('status', 'permission')->count(),
        ];

        // Get all guru who haven't checked in
        $allGuru = User::where('role', 'guru')->pluck('id');
        $checkedInGuru = Attendance::whereDate('date', $date)->pluck('user_id');
        $notCheckedIn = User::where('role', 'guru')
            ->whereNotIn('id', $checkedInGuru)
            ->get();

        // Get all teachers for dropdown
        $teachers = User::where('role', 'guru')
            ->orderBy('name')
            ->get();

        return view('admin.attendance.daily', compact(
            'attendances',
            'summary',
            'notCheckedIn',
            'date',
            'teachers'
        ));
    }

    public function monthly(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        $search = $request->get('search');

        $settings = AttendanceSetting::current();
        $workingDays = $settings->getWorkingDaysCount($month, $year);

        // Get all guru with their attendance stats
        $query = User::where('role', 'guru')
            ->select(
                'users.id',
                'users.name',
                'users.niy',
                'users.email'
            )
            ->leftJoin('attendances', function($join) use ($month, $year) {
                $join->on('users.id', '=', 'attendances.user_id')
                    ->whereYear('attendances.date', $year)
                    ->whereMonth('attendances.date', $month);
            })
            ->selectRaw('
                COUNT(CASE WHEN attendances.status = "present" THEN 1 END) as present_count,
                COUNT(CASE WHEN attendances.status = "late" THEN 1 END) as late_count,
                COUNT(CASE WHEN attendances.status = "absent" THEN 1 END) as absent_count,
                COUNT(CASE WHEN attendances.status = "permission" THEN 1 END) as permission_count,
                SUM(CASE WHEN attendances.teaching_hours IS NOT NULL THEN attendances.teaching_hours ELSE 0 END) as total_teaching_hours
            ')
            ->groupBy(
                'users.id',
                'users.name',
                'users.niy',
                'users.email'
            );

        if ($search) {
            // Search by user_id if it's a valid ID
            if (is_numeric($search)) {
                $query->where('users.id', $search);
            } else {
                // Fallback for old search format (if manually entered)
                $query->where(function($q) use ($search) {
                    $q->where('users.name', 'like', "%{$search}%")
                      ->orWhere('users.niy', 'like', "%{$search}%");
                });
            }
        }

        $monthlyData = $query->paginate(20)->withQueryString();

        // Calculate averages
        $totalGuru = User::where('role', 'guru')->count();
        $averagePresent = $totalGuru > 0 ? ($monthlyData->sum('present_count') / $totalGuru) : 0;
        $averageLate = $totalGuru > 0 ? ($monthlyData->sum('late_count') / $totalGuru) : 0;
        $averageAbsent = $totalGuru > 0 ? ($monthlyData->sum('absent_count') / $totalGuru) : 0;

        // Get all teachers for dropdown
        $teachers = User::where('role', 'guru')
            ->orderBy('name')
            ->get();

        return view('admin.attendance.monthly', compact(
            'monthlyData',
            'totalGuru',
            'averagePresent',
            'averageLate',
            'averageAbsent',
            'workingDays',
            'month',
            'year',
            'teachers'
        ));
    }

    public function exportDailyPdf(Request $request)
    {
        $date = $request->get('date', now()->toDateString());
        $status = $request->get('status');

        $query = Attendance::with('user')
            ->whereDate('date', $date)
            ->orderBy('check_in_time');

        if ($status) {
            $query->where('status', $status);
        }

        $attendances = $query->get();

        $summary = [
            'present' => $attendances->where('status', 'present')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'permission' => $attendances->where('status', 'permission')->count(),
        ];

        $pdf = Pdf::loadView('admin.attendance.pdf.daily', compact(
            'attendances',
            'summary',
            'date'
        ))->setPaper('a4', 'landscape');

        $filename = 'Laporan-Absensi-Harian-' . Carbon::parse($date)->format('d-m-Y') . '.pdf';
        
        return $pdf->download($filename);
    }

    public function exportMonthlyPdf(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $settings = AttendanceSetting::current();
        $workingDays = $settings->getWorkingDaysCount($month, $year);

       $monthlyData = User::where('role', 'guru')
            ->select(
                'users.id',
                'users.name',
                'users.niy',
                'users.email'
            )
            ->leftJoin('attendances', function ($join) use ($month, $year) {
                $join->on('users.id', '=', 'attendances.user_id')
                    ->whereYear('attendances.date', $year)
                    ->whereMonth('attendances.date', $month);
            })
            ->selectRaw('
                COUNT(CASE WHEN attendances.status = "present" THEN 1 END) as present_count,
                COUNT(CASE WHEN attendances.status = "late" THEN 1 END) as late_count,
                COUNT(CASE WHEN attendances.status = "absent" THEN 1 END) as absent_count,
                COUNT(CASE WHEN attendances.status = \"permission\" THEN 1 END) as permission_count,
                SUM(CASE WHEN attendances.teaching_hours IS NOT NULL THEN attendances.teaching_hours ELSE 0 END) as total_teaching_hours
            ')
            ->groupBy(
                'users.id',
                'users.name',
                'users.niy',
                'users.email'
            )
            ->get();


        $totalGuru = $monthlyData->count();
       $totalPossible = $workingDays * $totalGuru;
        $averagePresent = $totalPossible > 0
            ? ($monthlyData->sum('present_count') / $totalPossible) * 100
            : 0;

        $averageLate = $totalPossible > 0
            ? ($monthlyData->sum('late_count') / $totalPossible) * 100
            : 0;

        $averageAbsent = $totalPossible > 0
            ? ($monthlyData->sum('absent_count') / $totalPossible) * 100
            : 0;


        $pdf = Pdf::loadView('admin.attendance.pdf.monthly', compact(
            'monthlyData',
            'totalGuru',
            'averagePresent',
            'averageLate',
            'averageAbsent',
            'workingDays',
            'month',
            'year'
        ))->setPaper('a4', 'landscape');

        $monthName = Carbon::create($year, $month, 1)->isoFormat('MMMM-YYYY');
        $filename = 'Laporan-Absensi-Bulanan-' . $monthName . '.pdf';
        
        return $pdf->download($filename);
    }

    public function exportMonthlyExcel(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $monthName = Carbon::create($year, $month, 1)->isoFormat('MMMM-YYYY');
        $filename = 'Laporan-Absensi-Bulanan-' . $monthName . '.xlsx';

        return Excel::download(new MonthlyAttendanceExport($month, $year), $filename);
    }
}