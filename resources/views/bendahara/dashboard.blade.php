<x-layout>
    <x-slot name="title">Dashboard Bendahara</x-slot>
    <x-slot name="header">Dashboard Bendahara</x-slot>

    <div class="p-4 md:p-8">
        <!-- Welcome Card -->
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-2xl p-6 mb-8 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold mb-2">Selamat Datang, {{ auth()->user()->name }}!</h2>
                    <p class="text-green-100">Dashboard Bendahara - {{ now()->isoFormat('MMMM YYYY') }}</p>
                </div>
                <div class="hidden md:block">
                    <i class="fas fa-wallet text-6xl text-white/30"></i>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Guru -->
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Guru</p>
                        <h3 class="text-3xl font-bold text-blue-700">{{ $totalGuru }}</h3>
                    </div>
                    <div class="bg-blue-100 rounded-full p-4">
                        <i class="fas fa-users text-2xl text-blue-600"></i>
                    </div>
                </div>
            </div>

            <!-- Total Jam Mengajar -->
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Jam Mengajar</p>
                        <h3 class="text-3xl font-bold text-purple-700">{{ $totalTeachingHours }}</h3>
                        <p class="text-xs text-gray-500">Bulan ini</p>
                    </div>
                    <div class="bg-purple-100 rounded-full p-4">
                        <i class="fas fa-chalkboard-teacher text-2xl text-purple-600"></i>
                    </div>
                </div>
            </div>

            <!-- Estimasi Total Gaji -->
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Estimasi Total Gaji</p>
                        <h3 class="text-2xl font-bold text-green-700">Rp {{ number_format($estimatedSalary, 0, ',', '.') }}</h3>
                        <p class="text-xs text-gray-500">@ Rp 10.000/jam</p>
                    </div>
                    <div class="bg-green-100 rounded-full p-4">
                        <i class="fas fa-money-bill-wave text-2xl text-green-600"></i>
                    </div>
                </div>
            </div>

            <!-- Rate per Jam -->
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Rate per Jam</p>
                        <h3 class="text-3xl font-bold text-yellow-700">Rp 10.000</h3>
                    </div>
                    <div class="bg-yellow-100 rounded-full p-4">
                        <i class="fas fa-coins text-2xl text-yellow-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Top Teachers -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-bolt text-yellow-500 mr-2"></i>Aksi Cepat
                </h3>
                <div class="space-y-3">
                    <a href="{{ route('bendahara.attendance.monthly') }}" class="flex items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                        <div class="bg-blue-500 text-white p-3 rounded-lg mr-4">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Rekap Absensi Bulanan</p>
                            <p class="text-sm text-gray-600">Lihat rekapan absensi dan jam mengajar guru</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400 ml-auto"></i>
                    </a>
                    
                    <a href="{{ route('bendahara.slip-gaji.index') }}" class="flex items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition">
                        <div class="bg-green-500 text-white p-3 rounded-lg mr-4">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Slip Gaji</p>
                            <p class="text-sm text-gray-600">Kelola slip gaji guru</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400 ml-auto"></i>
                    </a>
                    
                    <a href="{{ route('bendahara.slip-gaji.create') }}" class="flex items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition">
                        <div class="bg-purple-500 text-white p-3 rounded-lg mr-4">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Buat Slip Gaji</p>
                            <p class="text-sm text-gray-600">Generate slip gaji baru untuk guru</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400 ml-auto"></i>
                    </a>
                </div>
            </div>

            <!-- Top Teachers by Teaching Hours -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-trophy text-yellow-500 mr-2"></i>Top 5 Guru - Jam Mengajar Bulan Ini
                </h3>
                <div class="space-y-3">
                    @forelse($topTeachers as $index => $teacher)
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="w-8 h-8 flex items-center justify-center rounded-full mr-3
                                @if($index === 0) bg-yellow-500 text-white
                                @elseif($index === 1) bg-gray-400 text-white
                                @elseif($index === 2) bg-orange-400 text-white
                                @else bg-gray-200 text-gray-600 @endif">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800">{{ $teacher->name }}</p>
                                <p class="text-xs text-gray-500">{{ $teacher->niy }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-purple-600">{{ $teacher->total_hours ?? 0 }} jam</p>
                                <p class="text-xs text-gray-500">Rp {{ number_format(($teacher->total_hours ?? 0) * 10000, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 py-4">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>Belum ada data jam mengajar bulan ini</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-layout>
