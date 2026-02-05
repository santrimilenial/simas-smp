<x-layout>
    <x-slot name="title">Rekap Absensi Bulanan</x-slot>
    <x-slot name="header">Rekap Absensi Bulanan</x-slot>

    <div class="p-4 md:p-8">
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <!-- Filter Section -->
            <form method="GET" action="{{ route('bendahara.attendance.monthly') }}" class="mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Bulan</label>
                        <select name="month" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ request('month', now()->month) == $i ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($i)->isoFormat('MMMM') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun</label>
                        <select name="year" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            @for($i = now()->year; $i >= now()->year - 5; $i--)
                                <option value="{{ $i }}" {{ request('year', now()->year) == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Cari Guru</label>
                        <select name="search" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Semua Guru</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ request('search') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->name }} - {{ $teacher->niy }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                            <i class="fas fa-search mr-2"></i>Filter
                        </button>
                        <a href="{{ route('bendahara.attendance.monthly') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg font-semibold transition">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </div>
            </form>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-lg border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Total Guru</p>
                            <h3 class="text-2xl font-bold text-blue-700">{{ $totalGuru ?? 0 }}</h3>
                        </div>
                        <div class="bg-blue-200 rounded-full p-3">
                            <i class="fas fa-users text-xl text-blue-700"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-lg border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Total Jam Mengajar</p>
                            <h3 class="text-2xl font-bold text-purple-700">{{ $totalTeachingHours ?? 0 }}</h3>
                        </div>
                        <div class="bg-purple-200 rounded-full p-3">
                            <i class="fas fa-chalkboard-teacher text-xl text-purple-700"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-lg border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Estimasi Total Gaji</p>
                            <h3 class="text-xl font-bold text-green-700">Rp {{ number_format($totalEstimatedSalary ?? 0, 0, ',', '.') }}</h3>
                        </div>
                        <div class="bg-green-200 rounded-full p-3">
                            <i class="fas fa-money-bill-wave text-xl text-green-700"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-4 rounded-lg border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Hari Kerja</p>
                            <h3 class="text-2xl font-bold text-yellow-700">{{ $workingDays ?? 0 }}</h3>
                        </div>
                        <div class="bg-yellow-200 rounded-full p-3">
                            <i class="fas fa-calendar-alt text-xl text-yellow-700"></i>
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
                    <a href="{{ route('bendahara.slip-gaji.create', ['month' => $month, 'year' => $year]) }}" 
                       class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                        <i class="fas fa-file-invoice-dollar mr-2"></i>Buat Slip Gaji
                    </a>
                    <a href="{{ route('bendahara.attendance.monthly.pdf', request()->all()) }}" 
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
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Jam Mengajar</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Estimasi Gaji</th>
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
                                        <div class="flex-shrink-0 h-10 w-10 bg-green-100 rounded-full flex items-center justify-center">
                                            <span class="text-green-600 font-semibold">{{ substr($data->name, 0, 1) }}</span>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-semibold text-gray-900">{{ $data->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $data->niy }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center justify-center w-10 h-10 bg-green-100 text-green-800 rounded-full font-bold">
                                        {{ $data->present_count }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center justify-center w-10 h-10 bg-yellow-100 text-yellow-800 rounded-full font-bold">
                                        {{ $data->late_count }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center justify-center w-10 h-10 bg-blue-100 text-blue-800 rounded-full font-bold">
                                        {{ $data->permission_count }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center justify-center bg-purple-100 text-purple-800 px-4 py-2 rounded-lg font-bold">
                                        <i class="fas fa-clock mr-2"></i>{{ $data->total_teaching_hours ?? 0 }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center justify-center bg-green-100 text-green-800 px-4 py-2 rounded-lg font-bold">
                                        Rp {{ number_format(($data->total_teaching_hours ?? 0) * 10000, 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    <i class="fas fa-inbox text-4xl mb-3"></i>
                                    <p>Belum ada data absensi untuk periode ini</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($monthlyData->count() > 0)
                    <tfoot class="bg-gray-100">
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-right font-bold text-gray-700">Total:</td>
                            <td class="px-6 py-4 text-center font-bold text-purple-700">
                                {{ $totalTeachingHours }} Jam
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-green-700">
                                Rp {{ number_format($totalEstimatedSalary, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>

            <!-- Pagination -->
            @if($monthlyData->hasPages())
                <div class="mt-4">
                    {{ $monthlyData->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layout>
