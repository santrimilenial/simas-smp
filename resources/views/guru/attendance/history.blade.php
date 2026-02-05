<x-layout>
    <x-slot name="title">Riwayat Absensi Saya</x-slot>
    <x-slot name="header">Riwayat Absensi Saya</x-slot>

    <!-- Summary Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 md:gap-4 mb-6 md:mb-8 px-4 md:px-0">
        <div class="bg-white rounded-xl shadow-sm p-3 md:p-4 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-gray-600 text-xs md:text-sm">Total Hadir</p>
                    <h3 class="text-xl md:text-2xl font-bold text-gray-800 mt-1">{{ $summary['present'] ?? 0 }}</h3>
                    <p class="text-xs text-green-600 mt-1">Bulan Ini</p>
                </div>
                <div class="bg-green-100 rounded-full p-2 md:p-3 flex-shrink-0">
                    <i class="fas fa-check-circle text-lg md:text-xl text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-3 md:p-4 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-gray-600 text-xs md:text-sm">Terlambat</p>
                    <h3 class="text-xl md:text-2xl font-bold text-gray-800 mt-1">{{ $summary['late'] ?? 0 }}</h3>
                    <p class="text-xs text-yellow-600 mt-1">Bulan Ini</p>
                </div>
                <div class="bg-yellow-100 rounded-full p-2 md:p-3 flex-shrink-0">
                    <i class="fas fa-clock text-lg md:text-xl text-yellow-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-3 md:p-4 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-gray-600 text-xs md:text-sm">Izin</p>
                    <h3 class="text-xl md:text-2xl font-bold text-gray-800 mt-1">{{ $summary['permission'] ?? 0 }}</h3>
                    <p class="text-xs text-blue-600 mt-1">Bulan Ini</p>
                </div>
                <div class="bg-blue-100 rounded-full p-2 md:p-3 flex-shrink-0">
                    <i class="fas fa-file-alt text-lg md:text-xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-3 md:p-4 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-gray-600 text-xs md:text-sm">Sakit</p>
                    <h3 class="text-xl md:text-2xl font-bold text-gray-800 mt-1">{{ $summary['sick'] ?? 0 }}</h3>
                    <p class="text-xs text-purple-600 mt-1">Bulan Ini</p>
                </div>
                <div class="bg-purple-100 rounded-full p-2 md:p-3 flex-shrink-0">
                    <i class="fas fa-notes-medical text-lg md:text-xl text-purple-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-3 md:p-4 border-l-4 border-indigo-500">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-gray-600 text-xs md:text-sm">Total Hari</p>
                    <h3 class="text-xl md:text-2xl font-bold text-gray-800 mt-1">{{ $summary['total'] ?? 0 }}</h3>
                    <p class="text-xs text-indigo-600 mt-1">Bulan Ini</p>
                </div>
                <div class="bg-indigo-100 rounded-full p-2 md:p-3 flex-shrink-0">
                    <i class="fas fa-calendar-day text-lg md:text-xl text-indigo-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-3 md:p-4 border-l-4 border-emerald-500">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-gray-600 text-xs md:text-sm">Kehadiran</p>
                    <h3 class="text-xl md:text-2xl font-bold text-gray-800 mt-1">{{ number_format($summary['percentage'] ?? 0, 1) }}%</h3>
                    <p class="text-xs text-emerald-600 mt-1">Bulan Ini</p>
                </div>
                <div class="bg-emerald-100 rounded-full p-2 md:p-3 flex-shrink-0">
                    <i class="fas fa-chart-pie text-lg md:text-xl text-emerald-600"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4 md:p-6 mx-4 md:mx-0">
        <!-- Filter Section -->
        <form method="GET" action="{{ route('guru.attendance.history') }}" class="mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2 md:gap-4">
                <div>
                    <label class="block text-xs md:text-sm font-semibold text-gray-700 mb-2">Bulan</label>
                    <select name="month" class="w-full px-3 md:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ request('month', now()->month) == $i ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($i)->isoFormat('MMMM') }}
                            </option>
                        @endfor
                    </select>
                </div>
                
                <div>
                    <label class="block text-xs md:text-sm font-semibold text-gray-700 mb-2">Tahun</label>
                    <select name="year" class="w-full px-3 md:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        @for($i = now()->year; $i >= now()->year - 2; $i--)
                            <option value="{{ $i }}" {{ request('year', now()->year) == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="block text-xs md:text-sm font-semibold text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-3 md:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        <option value="">Semua Status</option>
                        <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Hadir Tepat Waktu</option>
                        <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Hadir Terlambat</option>
                        <option value="permission" {{ request('status') == 'permission' ? 'selected' : '' }}>Izin</option>
                        <option value="sick" {{ request('status') == 'sick' ? 'selected' : '' }}>Sakit</option>
                    </select>
                </div>

                <div class="flex items-end gap-1 md:gap-2">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 md:px-4 py-2 rounded-lg font-semibold transition text-xs md:text-sm">
                        <i class="fas fa-search mr-1 md:mr-2"></i><span class="hidden sm:inline">Filter</span>
                    </button>
                    <a href="{{ route('guru.attendance.history') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg font-semibold transition">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </div>
        </form>

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-gray-800">
                Riwayat Absensi - {{ \Carbon\Carbon::create()->month((int)request('month', now()->month))->isoFormat('MMMM') }} {{ request('year', now()->year) }}
            </h3>
        </div>

        <!-- List View -->
        <div class="space-y-3">
            @forelse($attendances as $attendance)
                @php
                    // Tentukan apakah terlambat atau tidak
                    $isLate = false;
                    if ($attendance->check_in_status === 'present' && $attendance->check_in_time) {
                        $settings = \App\Models\AttendanceSetting::current();
                        $checkInTime = $attendance->check_in_time;
                        $lateTime = \Carbon\Carbon::parse($settings->actual_late_time ?? '07:00');
                        $isLate = $checkInTime->greaterThan($lateTime);
                    }
                    
                    // Tentukan status display berdasarkan kondisi
                    $displayStatus = $attendance->check_in_status;
                    if ($displayStatus === 'present' && $isLate) {
                        $displayStatus = 'late';
                    }
                @endphp
                <div class="border-l-4 
                    @if($displayStatus === 'present') border-green-500 bg-green-50
                    @elseif($displayStatus === 'late') border-yellow-500 bg-yellow-50
                    @elseif($displayStatus === 'sick') border-purple-500 bg-purple-50
                    @elseif($displayStatus === 'permission') border-blue-500 bg-blue-50
                    @elseif($displayStatus === 'absent') border-red-500 bg-red-50
                    @else border-gray-500 bg-gray-50
                    @endif
                    pl-4 py-4 pr-4 rounded-r-lg">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <div class="flex items-center gap-4">
                            <div class="flex-shrink-0 w-16 h-16 rounded-full flex flex-col items-center justify-center
                                @if($displayStatus === 'present') bg-green-100
                                @elseif($displayStatus === 'late') bg-yellow-100
                                @elseif($displayStatus === 'sick') bg-purple-100
                                @elseif($displayStatus === 'permission') bg-blue-100
                                @else bg-gray-100
                                @endif">
                                <span class="text-xs text-gray-600">{{ $attendance->date->format('D') }}</span>
                                <span class="text-2xl font-bold 
                                    @if($displayStatus === 'present') text-green-700
                                    @elseif($displayStatus === 'late') text-yellow-700
                                    @elseif($displayStatus === 'sick') text-purple-700
                                    @elseif($displayStatus === 'permission') text-blue-700
                                    @else text-gray-700
                                    @endif">
                                    {{ $attendance->date->format('d') }}
                                </span>
                            </div>
                            
                            <div>
                                <h4 class="font-bold text-gray-800">{{ $attendance->date->isoFormat('dddd, D MMMM YYYY') }}</h4>
                                
                                @if(in_array($attendance->check_in_status, ['present']) || in_array($attendance->status, ['present', 'late']))
                                    <div class="flex flex-col gap-3 mt-2 text-sm text-gray-600">
                                        <div class="flex items-center justify-between bg-white p-3 rounded border border-gray-200">
                                            <div>
                                                <i class="fas fa-sign-in-alt mr-2 text-green-600"></i>
                                                <span>Masuk: <strong>{{ $attendance->formatted_check_in ?? '-' }}</strong></span>
                                            </div>
                                            @if($attendance->check_in_status === 'present')
                                                @if($isLate)
                                                    <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded whitespace-nowrap ml-2">
                                                            ⏱️ Terlambat
                                                    </span>
                                                @else
                                                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded whitespace-nowrap ml-2">
                                                        ✅ Tepat Waktu
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                        
                                        <div class="flex items-center justify-between bg-white p-3 rounded border border-gray-200">
                                            <div>
                                                <i class="fas fa-sign-out-alt mr-2 text-purple-600"></i>
                                                <span>Pulang: <strong>{{ $attendance->formatted_check_out ?? '-' }}</strong></span>
                                            </div>
                                            @if($attendance->check_out_status)
                                                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded whitespace-nowrap ml-2">
                                                    {{ $attendance->check_out_status_label }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                
                                @if($attendance->check_in_status)
                                    <p class="text-sm text-gray-700 mt-2 bg-white p-2 rounded border border-gray-200">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        <strong>Status Berangkat:</strong>
                                        @if($attendance->check_in_status === 'present')
                                            {{ $isLate ? 'Hadir Terlambat' : 'Hadir Tepat Waktu' }}
                                        @else
                                            {{ $attendance->check_in_status_label }}
                                        @endif
                                        @if($attendance->check_in_reason)
                                            - {{ $attendance->check_in_reason }}
                                        @endif
                                    </p>
                                @endif
                                
                                @if($attendance->check_out_reason)
                                    <p class="text-sm text-gray-700 mt-2 bg-white p-2 rounded border border-gray-200">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        <strong>Alasan Pulang:</strong> {{ $attendance->check_out_reason }}
                                    </p>
                                @endif
                                
                                @if($attendance->notes)
                                    <p class="text-xs text-gray-600 mt-2 italic">
                                        <i class="fas fa-sticky-note mr-1"></i>
                                        {{ $attendance->notes }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex flex-col items-end gap-2">
                            <span class="px-4 py-2 rounded-full text-sm font-semibold
                                @if($displayStatus === 'present') bg-green-100 text-green-800
                                @elseif($displayStatus === 'late') bg-yellow-100 text-yellow-800
                                @elseif($displayStatus === 'sick') bg-purple-100 text-purple-800
                                @elseif($displayStatus === 'permission') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                @if($displayStatus === 'permission')
                                    <i class="fas fa-file-alt mr-1"></i>
                                @elseif($displayStatus === 'sick')
                                    <i class="fas fa-notes-medical mr-1"></i>
                                @elseif($displayStatus === 'late')
                                    <i class="fas fa-clock mr-1"></i>
                                @elseif($displayStatus === 'present')
                                    <i class="fas fa-check-circle mr-1"></i>
                                @endif
                                @if($displayStatus === 'present')
                                    Hadir Tepat Waktu
                                @elseif($displayStatus === 'late')
                                    Hadir Terlambat
                                @elseif($displayStatus === 'permission')
                                    Izin
                                @elseif($displayStatus === 'sick')
                                    Sakit
                                @else
                                    {{ ucfirst($displayStatus) }}
                                @endif
                            </span>
                            
                            @if($attendance->check_in_time && $attendance->check_out_time)
                                @php
                                    $duration = \Carbon\Carbon::parse($attendance->check_in_time)
                                        ->diffInHours(\Carbon\Carbon::parse($attendance->check_out_time));
                                @endphp
                                <span class="text-xs text-gray-600">
                                    <i class="far fa-clock mr-1"></i>
                                    Durasi: {{ $duration }} jam
                                </span>
                            @elseif(in_array($attendance->status, ['permission', 'sick']))
                                <span class="text-xs text-gray-600">
                                    <i class="far fa-clock mr-1"></i>
                                    Durasi: Full Day
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 text-gray-500">
                    <i class="fas fa-calendar-times text-5xl mb-3"></i>
                    <p class="text-lg font-semibold">Belum ada data absensi</p>
                    <p class="text-sm mt-1">Data absensi Anda akan muncul di sini</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($attendances->hasPages())
            <div class="mt-6">
                {{ $attendances->links() }}
            </div>
        @endif
    </div>

    <!-- Statistics Chart -->
    @if(isset($chartData) && !empty($chartData['labels']))
    <div class="bg-white rounded-xl shadow-sm p-6 mt-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-chart-line mr-2 text-purple-600"></i>Grafik Kehadiran 6 Bulan Terakhir
        </h3>
        <canvas id="attendanceChart" class="w-full" style="max-height: 300px;"></canvas>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        const attendanceChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($chartData['labels']),
                datasets: [
                    {
                        label: 'Hadir',
                        data: @json($chartData['present']),
                        backgroundColor: 'rgba(34, 197, 94, 0.7)',
                        borderColor: 'rgb(34, 197, 94)',
                        borderWidth: 2
                    },
                    {
                        label: 'Terlambat',
                        data: @json($chartData['late']),
                        backgroundColor: 'rgba(234, 179, 8, 0.7)',
                        borderColor: 'rgb(234, 179, 8)',
                        borderWidth: 2
                    },
                    {
                        label: 'Izin',
                        data: @json($chartData['permission']),
                        backgroundColor: 'rgba(59, 130, 246, 0.7)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 2
                    },
                    {
                        label: 'Sakit',
                        data: @json($chartData['sick']),
                        backgroundColor: 'rgba(168, 85, 247, 0.7)',
                        borderColor: 'rgb(168, 85, 247)',
                        borderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
    @endpush
    @endif
</x-layout>