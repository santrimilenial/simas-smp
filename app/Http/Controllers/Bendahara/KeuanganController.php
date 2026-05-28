<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\FinancialRecord;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class KeuanganController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        $type = $request->get('type');
        $category = $request->get('category');

        $query = FinancialRecord::with('creator')
            ->forMonth($month, $year)
            ->recent();

        if ($type) {
            $query->where('type', $type);
        }

        if ($category) {
            $query->where('category', $category);
        }

        $records = $query->paginate(20)->withQueryString();

        // Stats
        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $prevIncome = FinancialRecord::where('record_date', '<', $startOfMonth)->income()->sum('amount');
        $prevExpense = FinancialRecord::where('record_date', '<', $startOfMonth)->expense()->sum('amount');
        $carryOver = $prevIncome - $prevExpense;

        $totalIncome = FinancialRecord::forMonth($month, $year)->income()->sum('amount');
        $totalExpense = FinancialRecord::forMonth($month, $year)->expense()->sum('amount');
        $totalRecords = FinancialRecord::forMonth($month, $year)->count();

        $stats = [
            'total_records' => $totalRecords,
            'carry_over' => $carryOver,
            'total_income' => $totalIncome,
            'total_expense' => $totalExpense,
            'balance' => $carryOver + $totalIncome - $totalExpense,
        ];

        // Get unique categories for filter
        $categories = FinancialRecord::distinct()->pluck('category')->sort()->values();

        return view('bendahara.keuangan.index', compact('records', 'month', 'year', 'stats', 'categories', 'type', 'category'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'record_date' => 'required|date|before_or_equal:today',
            'type' => 'required|in:income,expense',
            'category' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'amount' => 'required|numeric|min:1',
            'notes' => 'nullable|string|max:1000',
        ], [
            'record_date.required' => 'Tanggal harus diisi',
            'record_date.before_or_equal' => 'Tanggal tidak boleh lebih dari hari ini',
            'type.required' => 'Jenis transaksi harus dipilih',
            'type.in' => 'Jenis transaksi tidak valid',
            'category.required' => 'Kategori harus diisi',
            'description.required' => 'Deskripsi harus diisi',
            'amount.required' => 'Jumlah harus diisi',
            'amount.min' => 'Jumlah minimal Rp 1',
        ]);

        $validated['created_by'] = auth()->id();

        FinancialRecord::create($validated);

        return redirect()->route('bendahara.keuangan.index')
            ->with('success', 'Catatan keuangan berhasil ditambahkan!');
    }

    public function update(Request $request, FinancialRecord $keuangan)
    {
        $validated = $request->validate([
            'record_date' => 'required|date|before_or_equal:today',
            'type' => 'required|in:income,expense',
            'category' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'amount' => 'required|numeric|min:1',
            'notes' => 'nullable|string|max:1000',
        ], [
            'record_date.required' => 'Tanggal harus diisi',
            'record_date.before_or_equal' => 'Tanggal tidak boleh lebih dari hari ini',
            'type.required' => 'Jenis transaksi harus dipilih',
            'type.in' => 'Jenis transaksi tidak valid',
            'category.required' => 'Kategori harus diisi',
            'description.required' => 'Deskripsi harus diisi',
            'amount.required' => 'Jumlah harus diisi',
            'amount.min' => 'Jumlah minimal Rp 1',
        ]);

        $keuangan->update($validated);

        return redirect()->route('bendahara.keuangan.index')
            ->with('success', 'Catatan keuangan berhasil diperbarui!');
    }

    public function destroy(FinancialRecord $keuangan)
    {
        $keuangan->delete();

        return redirect()->route('bendahara.keuangan.index')
            ->with('success', 'Catatan keuangan berhasil dihapus!');
    }

    public function exportPdf(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $records = FinancialRecord::with('creator')
            ->forMonth($month, $year)
            ->orderBy('record_date')
            ->orderBy('created_at')
            ->get();

        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $prevIncome = FinancialRecord::where('record_date', '<', $startOfMonth)->income()->sum('amount');
        $prevExpense = FinancialRecord::where('record_date', '<', $startOfMonth)->expense()->sum('amount');
        $carryOver = $prevIncome - $prevExpense;

        $totalIncome = FinancialRecord::forMonth($month, $year)->income()->sum('amount');
        $totalExpense = FinancialRecord::forMonth($month, $year)->expense()->sum('amount');

        $stats = [
            'total_records' => $records->count(),
            'carry_over' => $carryOver,
            'total_income' => $totalIncome,
            'total_expense' => $totalExpense,
            'balance' => $carryOver + $totalIncome - $totalExpense,
        ];

        $pdf = Pdf::loadView('bendahara.keuangan.pdf.monthly', compact('records', 'month', 'year', 'stats'))
            ->setPaper('a4', 'portrait');

        $monthName = Carbon::create($year, $month, 1)->isoFormat('MMMM-YYYY');
        $filename = 'Laporan-Keuangan-' . $monthName . '.pdf';

        return $pdf->download($filename);
    }
}
