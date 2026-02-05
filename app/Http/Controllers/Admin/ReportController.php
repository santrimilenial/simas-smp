<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeachingLog;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\JurnalExport;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());
        $guruId = $request->input('guru_id');
        $filterClass = $request->input('class');

        $logs = TeachingLog::with('user')
            ->when($guruId, fn($q) => $q->where('user_id', $guruId))
            ->when($filterClass, fn($q) => $q->byClass($filterClass))
            ->dateRange($startDate, $endDate)
            ->recent()
            ->paginate(20);

        $classes = config('classes.list');

        return view('admin.reports.index', compact('logs', 'startDate', 'endDate', 'guruId', 'filterClass', 'classes'));
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());
        $guruId = $request->input('guru_id');
        $filterClass = $request->input('class');

        $logs = TeachingLog::with(['user', 'academicYear'])
            ->when($guruId, fn($q) => $q->where('user_id', $guruId))
            ->when($filterClass, fn($q) => $q->byClass($filterClass))
            ->dateRange($startDate, $endDate)
            ->recent()
            ->get();

        $pdf = Pdf::loadView('admin.reports.pdf', compact('logs', 'startDate', 'endDate', 'filterClass'));
        
        $filename = 'laporan-jurnal-' . Carbon::now()->format('Y-m-d') . '.pdf';
        return $pdf->download($filename);
    }

    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());
        $guruId = $request->input('guru_id');
        $filterClass = $request->input('class');

        $filename = 'laporan-jurnal-' . Carbon::now()->format('Y-m-d') . '.xlsx';
        
        return Excel::download(
            new JurnalExport($startDate, $endDate, $guruId, $filterClass),
            $filename
        );
    }
}