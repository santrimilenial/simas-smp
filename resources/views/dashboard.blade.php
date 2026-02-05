<x-layout>
    <x-slot name="title">Dashboard Guru</x-slot>
    <x-slot name="header">Dashboard Guru</x-slot>

    <!-- Greeting & Absensi Card -->
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl shadow-lg p-6 mb-8 text-white">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex-1">
                <h2 class="text-2xl font-bold">Selamat Datang, {{ auth()->user()->name }}! 👋</h2>
                <p class="mt-2">NIY: {{ auth()->user()->niy }}</p>
                <p class="text-sm opacity-90 mt-1">{{ now()->isoFormat('dddd, D MMMM YYYY') }} • {{ now()->format('H:i') }} WIB</p>
            </div>
            <div class="flex gap-3">
                @php
                    $todayAttendance = auth()->user()->todayAttendance();
                @endphp
                
                <!-- Absen Berangkat -->
                @if(!$todayAttendance || !$todayAttendance->check_in_time)
                    <a href="{{ route('guru.attendance.checkin.form') }}" class="bg-white text-blue-600 hover:bg-blue-50 px-6 py-3 rounded-lg font-semibold shadow-lg transition">
                        <i class="fas fa-sign-in-alt mr-2"></i>Absen Berangkat
                    </a>
                @else
                    <div class="bg-white/20 backdrop-blur-sm px-6 py-3 rounded-lg text-center">
                        <p class="text-sm font-semibold">✅ Berangkat: {{ $todayAttendance->formatted_check_in }}</p>
                    </div>
                @endif

                <!-- Absen Pulang -->
                @if($todayAttendance && $todayAttendance->check_in_time)
                    @if(!$todayAttendance->check_out_time)
                        <a href="{{ route('guru.attendance.checkout.form') }}" class="bg-white text-purple-600 hover:bg-purple-50 px-6 py-3 rounded-lg font-semibold shadow-lg transition">
                            <i class="fas fa-sign-out-alt mr-2"></i>Absen Pulang
                        </a>
                    @else
                        <div class="bg-white/20 backdrop-blur-sm px-6 py-3 rounded-lg text-center">
                            <p class="text-sm font-semibold">✅ Pulang: {{ $todayAttendance->formatted_check_out }}</p>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Jurnal</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $totalJurnal }}</h3>
                </div>
                <div class="bg-blue-100 rounded-full p-4">
                    <i class="fas fa-book text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Jurnal Bulan Ini</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $jurnalBulanIni }}</h3>
                </div>
                <div class="bg-green-100 rounded-full p-4">
                    <i class="fas fa-calendar-alt text-2xl text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Jurnal Minggu Ini</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $jurnalMingguIni }}</h3>
                </div>
                <div class="bg-purple-100 rounded-full p-4">
                    <i class="fas fa-calendar-week text-2xl text-purple-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Jurnal Hari Ini</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $jurnalHariIni }}</h3>
                </div>
                <div class="bg-orange-100 rounded-full p-4">
                    <i class="fas fa-calendar-day text-2xl text-orange-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Logs -->
    <div class="grid grid-cols-1 gap-6">
        <!-- Recent Logs -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-clock mr-2 text-blue-600"></i>Jurnal Terbaru
            </h3>
            <div class="space-y-3">
                @forelse($recentLogs as $log)
                    <div class="border-l-4 border-blue-500 pl-4 py-3 bg-gray-50 rounded-r">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $log->subject }} - {{ $log->class }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ Str::limit($log->material, 60) }}</p>
                                <div class="flex gap-3 mt-2 text-xs text-gray-500">
                                    <span><i class="far fa-calendar mr-1"></i>{{ $log->log_date->format('d M Y') }}</span>
                                    <span><i class="far fa-clock mr-1"></i>Jam ke-{{ $log->time_slot }}</span>
                                </div>
                            </div>
                            <a href="{{ route('guru.jurnal.edit', $log) }}" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-3"></i>
                        <p>Belum ada jurnal</p>
                        <a href="{{ route('guru.jurnal.create') }}" class="text-blue-600 hover:underline mt-2 inline-block">
                            Buat jurnal pertama Anda
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Subject Statistics -->
    @if($subjectStats->count() > 0)
    <div class="bg-white rounded-xl shadow-sm p-6 mt-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-chart-bar mr-2 text-green-600"></i>Statistik Mata Pelajaran (Bulan Ini)
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            @foreach($subjectStats as $stat)
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-lg text-center">
                    <p class="text-sm text-gray-600 mb-1">{{ $stat->subject }}</p>
                    <p class="text-3xl font-bold text-blue-700">{{ $stat->total }}</p>
                    <p class="text-xs text-gray-500 mt-1">pertemuan</p>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</x-layout>
