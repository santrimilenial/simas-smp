<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TeachingLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    // Cache TTL untuk dashboard (5 menit)
    const CACHE_TTL = 300;

    public function index()
    {
        // Cache dashboard statistics
        $stats = Cache::remember('admin_dashboard_stats', self::CACHE_TTL, function () {
            return $this->getDashboardStats();
        });

        // Cache chart data
        $chartData = Cache::remember('admin_dashboard_charts', self::CACHE_TTL, function () {
            return $this->getChartData();
        });

        // Recent logs - tidak di-cache karena perlu real-time
        $recentLogs = TeachingLog::with('user')
            ->recent()
            ->limit(10)
            ->get();

        // Top guru bulan ini - cache 5 menit
        $topGuru = Cache::remember('admin_dashboard_top_guru', self::CACHE_TTL, function () {
            return User::guru()
                ->withCount(['teachingLogs' => function ($query) {
                    $query->whereMonth('log_date', Carbon::now()->month)
                        ->whereYear('log_date', Carbon::now()->year);
                }])
                ->orderBy('teaching_logs_count', 'desc')
                ->limit(5)
                ->get();
        });

        return view('admin.dashboard', array_merge(
            $stats,
            $chartData,
            compact('recentLogs', 'topGuru')
        ));
    }

    /**
     * Get dashboard statistics - optimized with fewer queries
     */
    private function getDashboardStats(): array
    {
        $now = Carbon::now();
        $today = Carbon::today();
        $lastMonth = $now->copy()->subMonth();

        // Single query untuk basic stats
        $totalGuru = User::guru()->count();
        $totalJurnal = TeachingLog::count();
        
        // Query gabungan untuk jurnal bulan ini dan hari ini
        $jurnalBulanIni = TeachingLog::whereMonth('log_date', $now->month)
            ->whereYear('log_date', $now->year)
            ->count();
        $jurnalHariIni = TeachingLog::whereDate('log_date', $today)->count();
        $jurnalKemarin = TeachingLog::whereDate('log_date', Carbon::yesterday())->count();

        // Query bulan lalu untuk growth
        $jurnalLastMonth = TeachingLog::whereMonth('log_date', $lastMonth->month)
            ->whereYear('log_date', $lastMonth->year)
            ->count();
        
        // Hitung total guru bulan lalu (guru yang sudah ada sebelum akhir bulan lalu)
        $guruLastMonth = User::guru()
            ->where('created_at', '<', $now->copy()->startOfMonth())
            ->count();

        // Calculate growth percentages
        $guruGrowth = $guruLastMonth > 0 
            ? round((($totalGuru - $guruLastMonth) / $guruLastMonth) * 100, 1) 
            : 0;
        
        $jurnalGrowth = $jurnalLastMonth > 0 
            ? round((($jurnalBulanIni - $jurnalLastMonth) / $jurnalLastMonth) * 100, 1) 
            : 0;

        $hariIniGrowth = $jurnalKemarin > 0 
            ? round((($jurnalHariIni - $jurnalKemarin) / $jurnalKemarin) * 100, 1) 
            : 0;

        $bulanIniGrowth = $jurnalGrowth;

        return compact(
            'totalGuru',
            'totalJurnal',
            'jurnalBulanIni',
            'jurnalHariIni',
            'guruGrowth',
            'jurnalGrowth',
            'hariIniGrowth',
            'bulanIniGrowth'
        );
    }

    /**
     * Get chart data - optimized with GROUP BY instead of loops
     */
    private function getChartData(): array
    {
        $now = Carbon::now();
        $today = Carbon::today();

        // ============================================
        // Chart Harian (7 hari terakhir) - OPTIMIZED
        // Single query dengan GROUP BY menggantikan 7 query loop
        // ============================================
        $sevenDaysAgo = $today->copy()->subDays(6);
        $dailyData = TeachingLog::selectRaw('DATE(log_date) as date, COUNT(*) as count')
            ->whereBetween('log_date', [$sevenDaysAgo, $today])
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $hariIniChartLabels = collect();
        $hariIniChartData = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            $dateStr = $date->format('Y-m-d');
            $hariIniChartLabels->push($date->format('d M'));
            $hariIniChartData->push($dailyData[$dateStr] ?? 0);
        }

        // ============================================
        // Chart Mingguan (per minggu dalam bulan ini) - OPTIMIZED
        // ============================================
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();
        
        $bulanIniChartLabels = collect();
        $bulanIniChartData = collect();
        
        // Single query untuk seluruh bulan, lalu group di PHP
        $monthlyRawData = TeachingLog::selectRaw('log_date')
            ->whereBetween('log_date', [$startOfMonth, $endOfMonth])
            ->get()
            ->groupBy(function ($item) use ($startOfMonth) {
                $weekNum = $startOfMonth->diffInWeeks(Carbon::parse($item->log_date));
                return min($weekNum, 3); // Max 4 minggu (0-3)
            });

        for ($i = 0; $i < 4; $i++) {
            $bulanIniChartLabels->push('W' . ($i + 1));
            $bulanIniChartData->push($monthlyRawData->get($i)?->count() ?? 0);
        }

        // ============================================
        // Chart Bulanan (6 bulan terakhir) - OPTIMIZED
        // Single query dengan GROUP BY menggantikan 6 query loop
        // ============================================
        $sixMonthsAgo = $now->copy()->subMonths(5)->startOfMonth();
        $monthlyData = TeachingLog::selectRaw('YEAR(log_date) as year, MONTH(log_date) as month, COUNT(*) as count')
            ->where('log_date', '>=', $sixMonthsAgo)
            ->groupByRaw('YEAR(log_date), MONTH(log_date)')
            ->get()
            ->keyBy(function ($item) {
                return $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT);
            });

        $jurnalChartLabels = collect();
        $jurnalChartData = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $key = $month->format('Y-m');
            $jurnalChartLabels->push($month->format('M'));
            $jurnalChartData->push($monthlyData->get($key)?->count ?? 0);
        }

        // ============================================
        // Chart Guru (kumulatif 6 bulan terakhir) - OPTIMIZED
        // ============================================
        $guruMonthlyData = User::guru()
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->where('created_at', '>=', $sixMonthsAgo)
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->get()
            ->keyBy(function ($item) {
                return $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT);
            });

        // Hitung guru sebelum 6 bulan lalu (baseline)
        $guruBaseline = User::guru()
            ->where('created_at', '<', $sixMonthsAgo)
            ->count();

        $guruChartLabels = collect();
        $guruChartData = collect();
        $cumulativeGuru = $guruBaseline;
        
        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $key = $month->format('Y-m');
            $cumulativeGuru += $guruMonthlyData->get($key)?->count ?? 0;
            $guruChartLabels->push($month->format('M'));
            $guruChartData->push($cumulativeGuru);
        }

        // ============================================
        // Donut Charts - Already optimized with GROUP BY
        // ============================================
        $colors = ['#6366f1', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981', '#3b82f6', '#14b8a6'];

        // Top 5 Mata Pelajaran
        $topSubjectsData = TeachingLog::selectRaw('subject, COUNT(*) as count')
            ->whereMonth('log_date', $now->month)
            ->whereYear('log_date', $now->year)
            ->groupBy('subject')
            ->orderByDesc('count')
            ->limit(5)
            ->get();
        
        if ($topSubjectsData->isEmpty()) {
            $topSubjectsData = TeachingLog::selectRaw('subject, COUNT(*) as count')
                ->groupBy('subject')
                ->orderByDesc('count')
                ->limit(5)
                ->get();
        }
        
        $topSubjects = $topSubjectsData->map(function($item, $index) use ($colors) {
            return (object) [
                'subject' => $item->subject,
                'count' => $item->count,
                'color' => $colors[$index % count($colors)]
            ];
        });

        // Top 5 Kelas
        $topClassesData = TeachingLog::selectRaw('class, COUNT(*) as count')
            ->whereMonth('log_date', $now->month)
            ->whereYear('log_date', $now->year)
            ->groupBy('class')
            ->orderByDesc('count')
            ->limit(5)
            ->get();
        
        if ($topClassesData->isEmpty()) {
            $topClassesData = TeachingLog::selectRaw('class, COUNT(*) as count')
                ->groupBy('class')
                ->orderByDesc('count')
                ->limit(5)
                ->get();
        }
        
        $topClasses = $topClassesData->map(function($item, $index) use ($colors) {
            return (object) [
                'class' => $item->class,
                'count' => $item->count,
                'color' => $colors[$index % count($colors)]
            ];
        });

        // Distribusi Jurnal per Periode
        $thisWeek = TeachingLog::whereBetween('log_date', [
            $now->copy()->startOfWeek(),
            $now->copy()->endOfWeek()
        ])->count();
        
        $thisMonth = TeachingLog::whereMonth('log_date', $now->month)
            ->whereYear('log_date', $now->year)
            ->count();
        
        $totalJurnal = TeachingLog::count();
        $older = max(0, $totalJurnal - $thisMonth);
        
        $jurnalDistribution = collect([
            (object) ['label' => 'Minggu Ini', 'count' => $thisWeek, 'color' => '#6366f1'],
            (object) ['label' => 'Bulan Ini', 'count' => max(0, $thisMonth - $thisWeek), 'color' => '#10b981'],
            (object) ['label' => 'Sebelumnya', 'count' => $older, 'color' => '#94a3b8']
        ])->filter(fn($item) => $item->count > 0);

        return compact(
            'hariIniChartLabels',
            'hariIniChartData',
            'bulanIniChartLabels',
            'bulanIniChartData',
            'jurnalChartLabels',
            'jurnalChartData',
            'guruChartLabels',
            'guruChartData',
            'topSubjects',
            'topClasses',
            'jurnalDistribution'
        );
    }

    /**
     * Clear dashboard cache (call this when data changes)
     */
    public static function clearCache(): void
    {
        Cache::forget('admin_dashboard_stats');
        Cache::forget('admin_dashboard_charts');
        Cache::forget('admin_dashboard_top_guru');
    }
}