<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Generator;

class ItemController extends Controller
{
    /**
     * Generate unique item code
     */
    private function generateItemCode()
    {
        // Get the highest code number from existing items (including soft deleted)
        $lastItem = Item::withTrashed()
            ->where('code', 'like', 'ITM%')
            ->orderByRaw('CAST(SUBSTRING(code, 4) AS UNSIGNED) DESC')
            ->first();
        
        if (!$lastItem) {
            return 'ITM001';
        }
        
        // Extract number from last code (e.g., ITM001 -> 001)
        $lastNumber = (int) substr($lastItem->code, 3);
        $newNumber = $lastNumber + 1;
        
        return 'ITM' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Item::with('latestScan')->latest()->paginate(20);
        return view('admin.items.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.items.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'condition' => 'required|in:baik,rusak ringan,rusak berat',
            'quantity' => 'required|integer|min:0',
            'price' => 'nullable|numeric|min:0',
            'purchase_date' => 'nullable|date',
        ]);

        // Generate unique code
        $code = $this->generateItemCode();
        $validated['code'] = $code;

        // Generate QR code
        $qrcode = new Generator;
        $qrcodeImage = $qrcode->format('svg')
            ->size(300)
            ->errorCorrection('H')
            ->generate($code);
        
        // Save QR code image
        $qrcodePath = 'qrcodes/' . $code . '.svg';
        $fullPath = public_path($qrcodePath);
        
        if (!file_exists(public_path('qrcodes'))) {
            mkdir(public_path('qrcodes'), 0777, true);
        }
        
        file_put_contents($fullPath, $qrcodeImage);
        
        $validated['barcode_path'] = $qrcodePath;
        
        Item::create($validated);

        return redirect()->route('admin.items.index')
            ->with('success', 'Barang berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        $item->load(['scans.user']);
        return view('admin.items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        return view('admin.items.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'condition' => 'required|in:baik,rusak ringan,rusak berat',
            'quantity' => 'required|integer|min:0',
            'price' => 'nullable|numeric|min:0',
            'purchase_date' => 'nullable|date',
        ]);

        $item->update($validated);

        return redirect()->route('admin.items.index')
            ->with('success', 'Barang berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        // Delete QR code file
        if ($item->barcode_path && file_exists(public_path($item->barcode_path))) {
            unlink(public_path($item->barcode_path));
        }

        // Force delete (permanent delete)
        $item->forceDelete();

        return redirect()->route('admin.items.index')
            ->with('success', 'Barang berhasil dihapus!');
    }

    /**
     * Download QR code image
     */
    public function downloadBarcode(Item $item)
    {
        $path = public_path($item->barcode_path);
        
        if (!file_exists($path)) {
            abort(404, 'QR Code tidak ditemukan');
        }

        return response()->download($path, $item->code . '-qrcode.svg');
    }

    /**
     * Print QR code with item details
     */
    public function printBarcode(Item $item)
    {
        return view('admin.items.print', compact('item'));
    }
}
