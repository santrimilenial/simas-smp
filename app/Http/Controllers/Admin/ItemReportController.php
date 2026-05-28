<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Scan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ItemReportExport;
use Carbon\Carbon;

class ItemReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::query();

        // Filters
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('location')) {
            $query->where('location', $request->location);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        $items = $query->latest()->get();

        // Summary stats
        $totalItems = Item::count();
        $totalQuantity = Item::sum('quantity');
        $totalValue = Item::selectRaw('SUM(price * quantity) as total')->value('total') ?? 0;

        $conditionStats = [
            'baik' => Item::where('condition', 'baik')->count(),
            'rusak_ringan' => Item::where('condition', 'rusak ringan')->count(),
            'rusak_berat' => Item::where('condition', 'rusak berat')->count(),
        ];

        $categories = Item::whereNotNull('category')->distinct()->pluck('category')->sort();
        $locations = Item::whereNotNull('location')->distinct()->pluck('location')->sort();

        // Recent scans count (last 30 days)
        $recentScans = Scan::where('scanned_at', '>=', now()->subDays(30))->count();

        return view('admin.items.report', compact(
            'items', 'totalItems', 'totalQuantity', 'totalValue',
            'conditionStats', 'categories', 'locations', 'recentScans'
        ));
    }

    public function exportPdf(Request $request)
    {
        $query = Item::query();

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('location')) {
            $query->where('location', $request->location);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        $items = $query->latest()->get();

        $totalItems = $items->count();
        $totalQuantity = $items->sum('quantity');
        $totalValue = $items->sum(fn($item) => ($item->price ?? 0) * $item->quantity);

        $conditionStats = [
            'baik' => $items->where('condition', 'baik')->count(),
            'rusak_ringan' => $items->where('condition', 'rusak ringan')->count(),
            'rusak_berat' => $items->where('condition', 'rusak berat')->count(),
        ];

        $filters = [
            'condition' => $request->condition,
            'category' => $request->category,
            'location' => $request->location,
            'search' => $request->search,
        ];

        $pdf = Pdf::loadView('admin.items.report-pdf', compact(
            'items', 'totalItems', 'totalQuantity', 'totalValue', 'conditionStats', 'filters'
        ))->setPaper('a4', 'landscape');

        $filename = 'laporan-inventaris-' . Carbon::now()->format('Y-m-d') . '.pdf';
        return $pdf->download($filename);
    }

    public function exportExcel(Request $request)
    {
        $filename = 'laporan-inventaris-' . Carbon::now()->format('Y-m-d') . '.xlsx';

        return Excel::download(
            new ItemReportExport(
                $request->condition,
                $request->category,
                $request->location,
                $request->search
            ),
            $filename
        );
    }
}
