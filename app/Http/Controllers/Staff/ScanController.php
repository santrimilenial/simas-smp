<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Scan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ScanController extends Controller
{
    public function index()
    {
        return view('staff.scan.index');
    }

    /**
     * Lookup item by code — returns full item data without creating a scan
     */
    public function lookup(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $item = Item::where('code', $request->code)->first();

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Barang dengan kode tersebut tidak ditemukan!'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'item' => [
                'id' => $item->id,
                'name' => $item->name,
                'code' => $item->code,
                'category' => $item->category,
                'description' => $item->description,
                'location' => $item->location,
                'condition' => $item->condition,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'purchase_date' => $item->purchase_date?->format('d M Y'),
                'barcode_path' => $item->barcode_path ? asset($item->barcode_path) : null,
                'total_scans' => $item->total_scans,
                'last_scanned' => $item->latestScan?->scanned_at?->format('d M Y H:i'),
            ]
        ]);
    }

    public function scan(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string',
            'scan_type' => 'required|in:manual,camera',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'condition_report' => ['required', Rule::in(['baik', 'rusak ringan', 'rusak berat'])],
        ]);

        // Find item by code
        $item = Item::where('code', $validated['code'])->first();

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Barang tidak ditemukan!'
            ], 404);
        }

        // Create scan record with condition report
        Scan::create([
            'item_id' => $item->id,
            'user_id' => auth()->id(),
            'scanned_at' => now(),
            'scan_type' => $validated['scan_type'],
            'location' => $validated['location'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'condition_report' => $validated['condition_report'],
        ]);

        // Update item condition based on report
        $item->update(['condition' => $validated['condition_report']]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan kondisi barang berhasil disimpan!',
            'item' => [
                'name' => $item->name,
                'code' => $item->code,
                'category' => $item->category,
                'location' => $item->location,
                'condition' => $validated['condition_report'],
            ]
        ]);
    }

    public function history()
    {
        $scans = Scan::with('item')
            ->where('user_id', auth()->id())
            ->latest('scanned_at')
            ->paginate(20);

        return view('staff.scan.history', compact('scans'));
    }
}
