<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Scan;
use Illuminate\Http\Request;

class ScanController extends Controller
{
    public function index(Request $request)
    {
        $query = Scan::with(['item', 'user']);

        // Filter by staff
        if ($request->filled('staff_id')) {
            $query->where('user_id', $request->staff_id);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('scanned_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('scanned_at', '<=', $request->end_date);
        }

        // Filter by item
        if ($request->filled('item_id')) {
            $query->where('item_id', $request->item_id);
        }

        $scans = $query->latest('scanned_at')->paginate(20);

        return view('admin.scans.index', compact('scans'));
    }
}
