<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Attendance;
use App\Models\AttendanceSetting;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display monthly attendance recap for teachers
     */
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
            ->selectRaw("
                COUNT(CASE WHEN attendances.status = 'present' THEN 1 END) as present_count,
                COUNT(CASE WHEN attendances.status = 'late' THEN 1 END) as late_count,
                COUNT(CASE WHEN attendances.status = 'absent' THEN 1 END) as absent_count,
                COUNT(CASE WHEN attendances.status = 'permission' THEN 1 END) as permission_count,
                SUM(CASE WHEN attendances.teaching_hours IS NOT NULL THEN attendances.teaching_hours ELSE 0 END) as total_teaching_hours
            ")
            ->groupBy(
                'users.id',
                'users.name',
                'users.niy',
                'users.email'
            );

        if ($search) {
            if (is_numeric($search)) {
                $query->where('users.id', $search);
            } else {
                $query->where(function($q) use ($search) {
                    $q->where('users.name', 'like', "%{$search}%")
                      ->orWhere('users.niy', 'like', "%{$search}%");
                });
            }
        }

        $monthlyData = $query->paginate(20)->withQueryString();

        // Calculate totals
        $totalGuru = User::where('role', 'guru')->count();
        $totalTeachingHours = $monthlyData->sum('total_teaching_hours');
        $totalEstimatedSalary = $totalTeachingHours * 10000;

        // Get all teachers for dropdown
        $teachers = User::where('role', 'guru')
            ->orderBy('name')
            ->get();

        return view('bendahara.attendance.monthly', compact(
            'monthlyData',
            'totalGuru',
            'totalTeachingHours',
            'totalEstimatedSalary',
            'workingDays',
            'month',
            'year',
            'teachers'
        ));
    }

    /**
     * Export monthly attendance to PDF
     */
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
            ->leftJoin('attendances', function($join) use ($month, $year) {
                $join->on('users.id', '=', 'attendances.user_id')
                    ->whereYear('attendances.date', $year)
                    ->whereMonth('attendances.date', $month);
            })
            ->selectRaw("
                COUNT(CASE WHEN attendances.status = 'present' THEN 1 END) as present_count,
                COUNT(CASE WHEN attendances.status = 'late' THEN 1 END) as late_count,
                SUM(CASE WHEN attendances.teaching_hours IS NOT NULL THEN attendances.teaching_hours ELSE 0 END) as total_teaching_hours
            ")
            ->groupBy('users.id', 'users.name', 'users.niy', 'users.email')
            ->orderBy('users.name')
            ->get();

        $totalTeachingHours = $monthlyData->sum('total_teaching_hours');
        $totalEstimatedSalary = $totalTeachingHours * 10000;

        $pdf = Pdf::loadView('bendahara.attendance.pdf.monthly', compact(
            'monthlyData',
            'workingDays',
            'month',
            'year',
            'totalTeachingHours',
            'totalEstimatedSalary'
        ))->setPaper('a4', 'landscape');

        $filename = 'Rekap-Absensi-Bulanan-' . Carbon::create()->month($month)->isoFormat('MMMM') . '-' . $year . '.pdf';
        
        return $pdf->download($filename);
    }
}
