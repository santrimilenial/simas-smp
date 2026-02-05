<x-layout>
    <x-slot name="title">Laporan Absensi Bulanan</x-slot>
    <x-slot name="header">Laporan Absensi Bulanan</x-slot>

    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <!-- Filter Section -->
        <form method="GET" action="{{ route('admin.attendance.monthly') }}" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Bulan</label>
                    <select name="month" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ request('month', now()->month) == $i ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($i)->isoFormat('MMMM') }}
                            </option>
                        @endfor
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun</label>
                    <select name="year" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @for($i = now()->year; $i >= now()->year - 5; $i--)
                            <option value="{{ $i }}" {{ request('year', now()->year) == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Cari Guru</label>
                    <select name="search" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Guru</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ request('search') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }} - {{ $teacher->niy }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.attendance.monthly') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg font-semibold transition">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </div>
        </form>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-lg border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Guru</p>
                        <h3 class="text-2xl font-bold text-purple-700">{{ $totalGuru ?? 0 }}</h3>
                    </div>
                    <div class="bg-purple-200 rounded-full p-3">
                        <i class="fas fa-users text-xl text-purple-700"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-lg border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Rata-rata Hadir</p>
                        <h3 class="text-2xl font-bold text-green-700">{{ number_format($averagePresent ?? 0, 1) }}%</h3>
                    </div>
                    <div class="bg-green-200 rounded-full p-3">
                        <i class="fas fa-check-circle text-xl text-green-700"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-4 rounded-lg border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Rata-rata Terlambat</p>
                        <h3 class="text-2xl font-bold text-yellow-700">{{ number_format($averageLate ?? 0, 1) }}%</h3>
                    </div>
                    <div class="bg-yellow-200 rounded-full p-3">
                        <i class="fas fa-clock text-xl text-yellow-700"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-lg border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Hari Kerja</p>
                        <h3 class="text-2xl font-bold text-blue-700">{{ $workingDays ?? 0 }}</h3>
                    </div>
                    <div class="bg-blue-200 rounded-full p-3">
                        <i class="fas fa-calendar-alt text-xl text-blue-700"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 p-4 rounded-lg border-l-4 border-indigo-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Jam Mengajar</p>
                        <h3 class="text-2xl font-bold text-indigo-700">{{ $monthlyData->sum('total_teaching_hours') ?? 0 }}</h3>
                    </div>
                    <div class="bg-indigo-200 rounded-full p-3">
                        <i class="fas fa-chalkboard-teacher text-xl text-indigo-700"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Export Buttons -->
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">
                Rekap Absensi - {{ \Carbon\Carbon::create()->month((int)request('month', now()->month))->isoFormat('MMMM') }} {{ request('year', now()->year) }}
            </h3>
            <div class="flex gap-2">
                <a href="{{ route('admin.attendance.monthly.excel', request()->all()) }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                    <i class="fas fa-file-excel mr-2"></i>Export Excel
                </a>
                <a href="{{ route('admin.attendance.monthly.pdf', request()->all()) }}" 
                   class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                    <i class="fas fa-file-pdf mr-2"></i>Export PDF
                </a>
            </div>
        </div>

        <!-- Monthly Attendance Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama Guru</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Hadir</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Terlambat</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Izin</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Total Jam Mengajar</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Total Hari Kerja</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Persentase Kehadiran</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($monthlyData as $index => $data)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $monthlyData->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span class="text-blue-600 font-semibold">{{ substr($data->name, 0, 1) }}</span>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-semibold text-gray-900">{{ $data->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $data->niy }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center justify-center w-12 h-12 bg-green-100 text-green-800 rounded-full font-bold">
                                    {{ $data->present_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center justify-center w-12 h-12 bg-yellow-100 text-yellow-800 rounded-full font-bold">
                                    {{ $data->late_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center justify-center w-12 h-12 bg-blue-100 text-blue-800 rounded-full font-bold">
                                    {{ $data->permission_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex flex-col items-center">
                                    <span class="inline-flex items-center justify-center bg-purple-100 text-purple-800 px-4 py-2 rounded-lg font-bold text-lg">
                                        <i class="fas fa-chalkboard-teacher mr-2"></i>{{ $data->total_teaching_hours ?? 0 }}
                                    </span>
                                    <span class="text-xs text-gray-500 mt-1">Jam Pelajaran</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-semibold text-gray-900">
                                {{ $workingDays }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @php
                                    $percentage = $workingDays > 0 ? ($data->present_count / $workingDays) * 100 : 0;
                                @endphp
                                <div class="flex flex-col items-center">
                                    <span class="text-lg font-bold 
                                        @if($percentage >= 90) text-green-700
                                        @elseif($percentage >= 75) text-yellow-700
                                        @else text-red-700
                                        @endif">
                                        {{ number_format($percentage, 1) }}%
                                    </span>
                                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                        <div class="h-2 rounded-full 
                                            @if($percentage >= 90) bg-green-600
                                            @elseif($percentage >= 75) bg-yellow-600
                                            @else bg-red-600
                                            @endif" 
                                            style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <i class="fas fa-inbox text-5xl mb-3"></i>
                                    <p class="text-lg font-semibold">Tidak ada data</p>
                                    <p class="text-sm mt-1">Belum ada data absensi untuk bulan ini</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($monthlyData->hasPages())
            <div class="mt-6">
                {{ $monthlyData->links() }}
            </div>
        @endif
    </div>
</x-layout>