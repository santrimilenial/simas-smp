<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Scan;
use Illuminate\Http\Request;

class ScanController extends Controller
{
    public function index()
    {
        return view('staff.scan.index');
    }

    public function scan(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string',
            'scan_type' => 'required|in:manual,camera',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // Find item by code
        $item = Item::where('code', $validated['code'])->first();

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Barang tidak ditemukan!'
            ], 404);
        }

        // Create scan record
        Scan::create([
            'item_id' => $item->id,
            'user_id' => auth()->id(),
            'scanned_at' => now(),
            'scan_type' => $validated['scan_type'],
            'location' => $validated['location'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Barcode berhasil discan!',
            'item' => [
                'name' => $item->name,
                'code' => $item->code,
                'category' => $item->category,
                'location' => $item->location,
                'condition' => $item->condition,
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
