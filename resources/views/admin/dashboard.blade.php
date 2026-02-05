<x-layout>
    <x-slot name="title">Dashboard Admin</x-slot>
    <x-slot name="header">Dashboard Admin</x-slot>

    <!-- Statistics Cards with Charts -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Guru Card -->
        <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-lg transition-shadow duration-300">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wide">TOTAL GURU</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $totalGuru }}</h3>
                    @if(isset($guruGrowth))
                    <span class="inline-flex items-center text-sm font-semibold {{ $guruGrowth >= 0 ? 'text-green-600' : 'text-red-600' }} mt-2">
                        <i class="fas fa-arrow-{{ $guruGrowth >= 0 ? 'up' : 'down' }} mr-1"></i>
                        {{ abs($guruGrowth) }}%
                    </span>
                    @endif
                </div>
            </div>
            <div class="h-16 mt-4">
                <canvas id="guruChart"></canvas>
            </div>
        </div>

        <!-- Total Jurnal Card -->
        <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-lg transition-shadow duration-300">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wide">TOTAL JURNAL</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($totalJurnal) }}</h3>
                    @if(isset($jurnalGrowth))
                    <span class="inline-flex items-center text-sm font-semibold {{ $jurnalGrowth >= 0 ? 'text-green-600' : 'text-red-600' }} mt-2">
                        <i class="fas fa-arrow-{{ $jurnalGrowth >= 0 ? 'up' : 'down' }} mr-1"></i>
                        {{ abs($jurnalGrowth) }}%
                    </span>
                    @endif
                </div>
            </div>
            <div class="h-16 mt-4">
                <canvas id="jurnalChart"></canvas>
            </div>
        </div>

        <!-- Jurnal Bulan Ini Card -->
        <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-lg transition-shadow duration-300">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wide">JURNAL BULAN INI</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($jurnalBulanIni) }}</h3>
                    @if(isset($bulanIniGrowth))
                    <span class="inline-flex items-center text-sm font-semibold {{ $bulanIniGrowth >= 0 ? 'text-green-600' : 'text-red-600' }} mt-2">
                        <i class="fas fa-arrow-{{ $bulanIniGrowth >= 0 ? 'up' : 'down' }} mr-1"></i>
                        {{ abs($bulanIniGrowth) }}%
                    </span>
                    @endif
                </div>
            </div>
            <div class="h-16 mt-4">
                <canvas id="bulanIniChart"></canvas>
            </div>
        </div>

        <!-- Jurnal Hari Ini Card -->
        <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-lg transition-shadow duration-300">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wide">JURNAL HARI INI</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($jurnalHariIni) }}</h3>
                    @if(isset($hariIniGrowth))
                    <span class="inline-flex items-center text-sm font-semibold {{ $hariIniGrowth >= 0 ? 'text-green-600' : 'text-red-600' }} mt-2">
                        <i class="fas fa-arrow-{{ $hariIniGrowth >= 0 ? 'up' : 'down' }} mr-1"></i>
                        {{ abs($hariIniGrowth) }}%
                    </span>
                    @endif
                </div>
            </div>
            <div class="h-16 mt-4">
                <canvas id="hariIniChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Donut Charts -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Top Mata Pelajaran -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-book-open mr-2 text-indigo-600"></i>Mata Pelajaran Populer
            </h3>
            <div class="flex justify-center items-center" style="height: 200px;">
                <canvas id="subjectChart"></canvas>
            </div>
            <div class="mt-4 space-y-2">
                @if(isset($topSubjects))
                    @foreach($topSubjects as $subject)
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center">
                                <span class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $subject->color ?? '#6366f1' }}"></span>
                                <span class="text-gray-700">{{ $subject->subject }}</span>
                            </div>
                            <span class="font-semibold text-gray-800">{{ $subject->count }}</span>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Top Kelas -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-users mr-2 text-green-600"></i>Kelas Terpopuler
            </h3>
            <div class="flex justify-center items-center" style="height: 200px;">
                <canvas id="classChart"></canvas>
            </div>
            <div class="mt-4 space-y-2">
                @if(isset($topClasses))
                    @foreach($topClasses as $class)
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center">
                                <span class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $class->color ?? '#10b981' }}"></span>
                                <span class="text-gray-700">{{ $class->class }}</span>
                            </div>
                            <span class="font-semibold text-gray-800">{{ $class->count }}</span>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Distribusi Jurnal -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-chart-pie mr-2 text-orange-600"></i>Distribusi Jurnal
            </h3>
            <div class="flex justify-center items-center" style="height: 200px;">
                <canvas id="distributionChart"></canvas>
            </div>
            <div class="mt-4 space-y-2">
                @if(isset($jurnalDistribution))
                    @foreach($jurnalDistribution as $dist)
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center">
                                <span class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $dist->color ?? '#f59e0b' }}"></span>
                                <span class="text-gray-700">{{ $dist->label }}</span>
                            </div>
                            <span class="font-semibold text-gray-800">{{ $dist->count }}</span>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Logs & Top Teachers -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Logs -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-clock mr-2 text-blue-600"></i>Jurnal Terbaru
            </h3>
            <div class="space-y-3">
                @forelse($recentLogs as $log)
                    <div class="border-l-4 border-blue-500 pl-4 py-2">
                        <p class="font-semibold text-gray-800">{{ $log->user->name }}</p>
                        <p class="text-sm text-gray-600">{{ $log->subject }} - {{ $log->class }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $log->log_date->format('d M Y') }}</p>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">Belum ada jurnal</p>
                @endforelse
            </div>
        </div>

        <!-- Top Teachers -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-trophy mr-2 text-yellow-600"></i>Guru Teraktif Bulan Ini
            </h3>
            <div class="space-y-3">
                @forelse($topGuru as $guru)
                    <div class="flex items-center justify-between border-b pb-3">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $guru->name }}</p>
                            <p class="text-xs text-gray-500">{{ $guru->niy }}</p>
                        </div>
                        <div class="bg-blue-100 rounded-full px-4 py-2">
                            <span class="text-blue-700 font-bold">{{ $guru->teaching_logs_count }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">Belum ada data</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Common chart options for sparklines
        const sparklineOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { enabled: false }
            },
            scales: {
                x: { display: false },
                y: { display: false }
            },
            elements: {
                point: { radius: 0 },
                line: { 
                    borderWidth: 2,
                    tension: 0.4
                }
            }
        };

        // Guru Chart
        new Chart(document.getElementById('guruChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($guruChartLabels ?? ['', '', '', '', '', '', '']) !!},
                datasets: [{
                    data: {!! json_encode($guruChartData ?? [12, 15, 13, 18, 16, 20, $totalGuru]) !!},
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true
                }]
            },
            options: sparklineOptions
        });

        // Jurnal Chart
        new Chart(document.getElementById('jurnalChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($jurnalChartLabels ?? ['', '', '', '', '', '', '']) !!},
                datasets: [{
                    data: {!! json_encode($jurnalChartData ?? array_slice(array_merge([100, 120, 115, 140, 160, 180, 200], [$totalJurnal]), -7)) !!},
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    fill: true
                }]
            },
            options: sparklineOptions
        });

        // Bulan Ini Chart
        new Chart(document.getElementById('bulanIniChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($bulanIniChartLabels ?? ['', '', '', '', '', '', '']) !!},
                datasets: [{
                    data: {!! json_encode($bulanIniChartData ?? array_slice(array_merge([20, 25, 22, 30, 28, 35, 40], [$jurnalBulanIni]), -7)) !!},
                    borderColor: '#8b5cf6',
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    fill: true
                }]
            },
            options: sparklineOptions
        });

        // Hari Ini Chart
        new Chart(document.getElementById('hariIniChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($hariIniChartLabels ?? ['', '', '', '', '', '', '']) !!},
                datasets: [{
                    data: {!! json_encode($hariIniChartData ?? array_slice(array_merge([2, 3, 4, 3, 5, 4, 6], [$jurnalHariIni]), -7)) !!},
                    borderColor: '#f97316',
                    backgroundColor: 'rgba(249, 115, 22, 0.1)',
                    fill: true
                }]
            },
            options: sparklineOptions
        });

        // Donut Chart Options
        const donutOptions = {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    enabled: true,
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 13
                    }
                }
            },
            cutout: '70%'
        };

        // Top Subjects Chart
        @if(isset($topSubjects) && $topSubjects->count() > 0)
        new Chart(document.getElementById('subjectChart'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($topSubjects->pluck('subject')) !!},
                datasets: [{
                    data: {!! json_encode($topSubjects->pluck('count')) !!},
                    backgroundColor: {!! json_encode($topSubjects->pluck('color')) !!},
                    borderWidth: 0,
                    hoverOffset: 8
                }]
            },
            options: donutOptions
        });
        @else
        // Show empty state for subjects
        document.getElementById('subjectChart').parentElement.innerHTML = '<div class="text-center text-gray-500 py-8"><i class="fas fa-chart-pie text-4xl mb-2"></i><p class="text-sm">Belum ada data</p></div>';
        @endif

        // Top Classes Chart
        @if(isset($topClasses) && $topClasses->count() > 0)
        new Chart(document.getElementById('classChart'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($topClasses->pluck('class')) !!},
                datasets: [{
                    data: {!! json_encode($topClasses->pluck('count')) !!},
                    backgroundColor: {!! json_encode($topClasses->pluck('color')) !!},
                    borderWidth: 0,
                    hoverOffset: 8
                }]
            },
            options: donutOptions
        });
        @else
        // Show empty state for classes
        document.getElementById('classChart').parentElement.innerHTML = '<div class="text-center text-gray-500 py-8"><i class="fas fa-chart-pie text-4xl mb-2"></i><p class="text-sm">Belum ada data</p></div>';
        @endif

        // Distribution Chart
        @if(isset($jurnalDistribution) && $jurnalDistribution->count() > 0)
        new Chart(document.getElementById('distributionChart'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($jurnalDistribution->pluck('label')) !!},
                datasets: [{
                    data: {!! json_encode($jurnalDistribution->pluck('count')) !!},
                    backgroundColor: {!! json_encode($jurnalDistribution->pluck('color')) !!},
                    borderWidth: 0,
                    hoverOffset: 8
                }]
            },
            options: donutOptions
        });
        @else
        // Show empty state for distribution
        document.getElementById('distributionChart').parentElement.innerHTML = '<div class="text-center text-gray-500 py-8"><i class="fas fa-chart-pie text-4xl mb-2"></i><p class="text-sm">Belum ada data</p></div>';
        @endif
    </script>
</x-layout>