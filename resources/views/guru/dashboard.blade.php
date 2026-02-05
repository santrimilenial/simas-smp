<x-layout>
    <x-slot name="title">Dashboard Guru</x-slot>
    <x-slot name="header">Dashboard Guru</x-slot>

    <!-- Greeting & Absensi Card -->
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg md:rounded-xl shadow-lg p-4 md:p-6 mb-4 md:mb-8 text-white">
        <div class="flex flex-col gap-2 md:gap-4">
            <div class="flex-1">
                <h2 class="text-lg md:text-2xl font-bold">Selamat Datang, {{ auth()->user()->name }}! 👋</h2>
                <p class="mt-1 md:mt-2 text-sm md:text-base">NIY: {{ auth()->user()->niy }}</p>
                <p class="text-xs md:text-sm opacity-90 mt-1" id="current-datetime">{{ now()->isoFormat('dddd, D MMMM YYYY') }} • {{ now()->format('H:i') }} WIB</p>
            </div>
        </div>
    </div>
    
    <!-- Script untuk update waktu real-time -->
    <script>
        (function() {
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            
            function updateDateTime() {
                const now = new Date();
                const dayName = days[now.getDay()];
                const day = now.getDate();
                const month = months[now.getMonth()];
                const year = now.getFullYear();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');
                
                const dateTimeString = dayName + ', ' + day + ' ' + month + ' ' + year + ' • ' + hours + ':' + minutes + ':' + seconds + ' WIB';
                
                const element = document.getElementById('current-datetime');
                if (element) {
                    element.textContent = dateTimeString;
                }
            }
            
            setInterval(updateDateTime, 1000);
            updateDateTime();
        })();
    </script>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-4 md:mb-6">
        <div class="bg-white rounded-lg md:rounded-xl shadow-sm p-3 md:p-4 lg:p-6 border-l-4 border-blue-500">
            <div class="flex flex-col md:flex-row items-start md:items-center md:justify-between">
                <div class="mb-2 md:mb-0">
                    <p class="text-gray-600 text-xs md:text-sm">Total Jurnal</p>
                    <h3 class="text-xl md:text-2xl lg:text-3xl font-bold text-gray-800 mt-0.5 md:mt-1">{{ $totalJurnal }}</h3>
                </div>
                <div class="bg-blue-100 rounded-full p-2 md:p-3">
                    <i class="fas fa-book text-base md:text-lg lg:text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg md:rounded-xl shadow-sm p-3 md:p-4 lg:p-6 border-l-4 border-green-500">
            <div class="flex flex-col md:flex-row items-start md:items-center md:justify-between">
                <div class="mb-2 md:mb-0">
                    <p class="text-gray-600 text-xs md:text-sm">Jurnal Bulan Ini</p>
                    <h3 class="text-xl md:text-2xl lg:text-3xl font-bold text-gray-800 mt-0.5 md:mt-1">{{ $jurnalBulanIni }}</h3>
                </div>
                <div class="bg-green-100 rounded-full p-2 md:p-3">
                    <i class="fas fa-calendar-alt text-base md:text-lg lg:text-2xl text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg md:rounded-xl shadow-sm p-3 md:p-4 lg:p-6 border-l-4 border-purple-500">
            <div class="flex flex-col md:flex-row items-start md:items-center md:justify-between">
                <div class="mb-2 md:mb-0">
                    <p class="text-gray-600 text-xs md:text-sm">Jurnal Minggu Ini</p>
                    <h3 class="text-xl md:text-2xl lg:text-3xl font-bold text-gray-800 mt-0.5 md:mt-1">{{ $jurnalMingguIni }}</h3>
                </div>
                <div class="bg-purple-100 rounded-full p-2 md:p-3">
                    <i class="fas fa-calendar-week text-base md:text-lg lg:text-2xl text-purple-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg md:rounded-xl shadow-sm p-3 md:p-4 lg:p-6 border-l-4 border-orange-500">
            <div class="flex flex-col md:flex-row items-start md:items-center md:justify-between">
                <div class="mb-2 md:mb-0">
                    <p class="text-gray-600 text-xs md:text-sm">Jurnal Hari Ini</p>
                    <h3 class="text-xl md:text-2xl lg:text-3xl font-bold text-gray-800 mt-0.5 md:mt-1">{{ $jurnalHariIni }}</h3>
                </div>
                <div class="bg-orange-100 rounded-full p-2 md:p-3">
                    <i class="fas fa-calendar-day text-base md:text-lg lg:text-2xl text-orange-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Recent Logs -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 md:gap-4">
        <!-- Quick Actions -->
        <div class="bg-white rounded-lg md:rounded-xl shadow-sm p-4 md:p-5 lg:p-6">
            <h3 class="text-sm md:text-base lg:text-lg font-bold text-gray-800 mb-3 md:mb-4">
                <i class="fas fa-bolt mr-2 text-yellow-500"></i>Aksi Cepat
            </h3>
            <div class="space-y-2 md:space-y-3">
                <button onclick="openModal('modalCreateJurnal')"
                    class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white p-3 rounded-lg text-center text-xs md:text-sm lg:text-base font-semibold">
                    <i class="fas fa-plus-circle mr-2"></i>Tambah Jurnal Baru
                </button>
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('guru.attendance.checkin.form') }}"
                        class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white p-3 rounded-lg text-center text-xs md:text-sm font-semibold transition">
                        <i class="fas fa-sign-in-alt mr-1"></i>Absen Masuk
                    </a>
                    <a href="{{ route('guru.attendance.checkout.form') }}"
                        class="bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white p-3 rounded-lg text-center text-xs md:text-sm font-semibold transition">
                        <i class="fas fa-sign-out-alt mr-1"></i>Absen Pulang
                    </a>
                </div>
                <a href="{{ route('guru.jurnal.index') }}" class="block bg-gray-100 hover:bg-gray-200 text-gray-800 p-3 rounded-lg text-center text-xs md:text-sm lg:text-base font-semibold transition">
                    <i class="fas fa-list mr-2"></i>Lihat Semua Jurnal
                </a>
                <a href="{{ route('guru.reports.index') }}" class="block bg-gray-100 hover:bg-gray-200 text-gray-800 p-3 rounded-lg text-center text-xs md:text-sm lg:text-base font-semibold transition">
                    <i class="fas fa-file-download mr-2"></i>Download Laporan
                </a>
            </div>
        </div>

        <!-- Recent Logs -->
        <div class="bg-white rounded-lg md:rounded-xl shadow-sm p-4 md:p-5 lg:p-6 lg:col-span-2">
            <h3 class="text-sm md:text-base lg:text-lg font-bold text-gray-800 mb-3 md:mb-4">
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
    <div class="bg-white rounded-xl shadow-sm p-4 md:p-6 mt-4 md:mt-6">
        <h3 class="text-base md:text-lg font-bold text-gray-800 mb-3 md:mb-4">
            <i class="fas fa-chart-bar mr-2 text-green-600"></i>Statistik Mata Pelajaran (Bulan Ini)
        </h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 md:gap-4">
            @foreach($subjectStats as $stat)
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-3 md:p-4 rounded-lg text-center">
                    <p class="text-xs md:text-sm text-gray-600 mb-1">{{ $stat->subject }}</p>
                    <p class="text-2xl md:text-3xl font-bold text-blue-700">{{ $stat->total }}</p>
                    <p class="text-xs text-gray-500 mt-1">pertemuan</p>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Attendance History -->
    <div class="bg-white rounded-xl shadow-sm p-4 md:p-6 mt-4 md:mt-6">
        <h3 class="text-base md:text-lg font-bold text-gray-800 mb-3 md:mb-4">
            <i class="fas fa-history mr-2 text-indigo-600"></i>Riwayat Absensi (7 Hari Terakhir)
        </h3>
        <div class="space-y-3">
            @forelse($attendanceHistory ?? [] as $attendance)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center
                            @if($attendance->status === 'present') bg-green-100
                            @elseif($attendance->status === 'late') bg-yellow-100
                            @else bg-red-100
                            @endif">
                            <i class="fas 
                                @if($attendance->status === 'present') fa-check text-green-600
                                @elseif($attendance->status === 'late') fa-clock text-yellow-600
                                @else fa-times text-red-600
                                @endif text-xl"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $attendance->date->isoFormat('dddd, D MMM') }}</p>
                            <p class="text-xs text-gray-600">
                                Masuk: {{ $attendance->formatted_check_in ?? '-' }} • 
                                Pulang: {{ $attendance->formatted_check_out ?? '-' }}
                            </p>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        @if($attendance->status === 'present') bg-green-100 text-green-800
                        @elseif($attendance->status === 'late') bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ $attendance->status_label }}
                    </span>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-calendar-times text-4xl mb-3"></i>
                    <p>Belum ada riwayat absensi</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Monthly Performance Chart -->
    @if(isset($monthlyPerformance) && $monthlyPerformance->count() > 0)
    <div class="bg-white rounded-xl shadow-sm p-6 mt-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-chart-line mr-2 text-purple-600"></i>Grafik Performa Bulanan
        </h3>
        <canvas id="monthlyChart" class="w-full" style="max-height: 300px;"></canvas>
    </div>

    @push('scripts')
    <script>
        // Update waktu real-time - langsung dijalankan
        (function() {
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            
            function updateDateTime() {
                const now = new Date();
                const dayName = days[now.getDay()];
                const day = now.getDate();
                const month = months[now.getMonth()];
                const year = now.getFullYear();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');
                
                const dateTimeString = `${dayName}, ${day} ${month} ${year} • ${hours}:${minutes}:${seconds} WIB`;
                
                const element = document.getElementById('current-datetime');
                if (element) {
                    element.textContent = dateTimeString;
                }
            }
            
            // Update setiap detik
            setInterval(updateDateTime, 1000);
            // Update langsung
            updateDateTime();
        })();
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const params = new URLSearchParams(window.location.search);
            if (params.get('open') === 'create') {
                document.getElementById('modalCreateJurnal')?.classList.remove('hidden');
            }
        });

        const ctx = document.getElementById('monthlyChart').getContext('2d');
        const monthlyChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($monthlyPerformance->pluck('month')),
                datasets: [{
                    label: 'Jumlah Jurnal',
                    data: @json($monthlyPerformance->pluck('total')),
                    borderColor: 'rgb(99, 102, 241)',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
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

    <!-- Tips & Reminders -->
    <div class="bg-gradient-to-r from-teal-500 to-cyan-600 rounded-xl shadow-sm p-6 mt-6 text-white">
        <div class="flex items-start gap-4">
            <div class="bg-white/20 rounded-full p-3 flex-shrink-0">
                <i class="fas fa-lightbulb text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold mb-2">💡 Tips Hari Ini</h3>
                <p class="text-sm opacity-90">
                    Jangan lupa untuk selalu mengisi jurnal mengajar tepat waktu setelah selesai mengajar. 
                    Dokumentasi yang baik akan membantu evaluasi pembelajaran dan penyusunan laporan.
                </p>
            </div>
        </div>
    </div>

    <!-- Modal Check-in -->
    <x-modal id="modalCheckIn" title="📅 Absensi Masuk" size="max-w-lg">
        <form action="{{ route('guru.attendance.checkin') }}" method="POST" id="formCheckIn">
            @csrf
            @php
                $settings = \App\Models\AttendanceSetting::current();
                $isLate = $settings->isLate(now());
                $canCheckIn = $settings->canCheckIn(now());
            @endphp
            
            <!-- Warning if not yet time to check in -->
            @if (!$canCheckIn)
                <div class="bg-yellow-50 border-l-4 border-yellow-500 rounded-lg p-4 mb-6">
                    <div class="flex items-start gap-3">
                        <div class="text-2xl">⏰</div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-yellow-800 mb-2">Belum Waktunya Absen</h3>
                            <p class="text-yellow-700 text-sm">
                                Absen masuk dimulai pada pukul <strong>{{ $settings->formatted_check_in_time }} WIB</strong>.
                                Silakan kembali lagi saat waktu absen sudah dimulai.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
            
            <div class="{{ !$canCheckIn ? 'opacity-50 pointer-events-none' : '' }}">
            <!-- Info Waktu -->
            <div class="text-center mb-6">
                <div class="inline-block bg-blue-100 rounded-full p-4 mb-4">
                    <i class="fas fa-clock text-4xl text-blue-600"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">{{ now()->format('H:i') }} WIB</h3>
                <p class="text-sm text-gray-600 mt-2">
                    Batas waktu: <strong>{{ $settings->formatted_late_time }} WIB</strong>
                    @if($settings->grace_period > 0)
                        <span class="text-xs">(+{{ $settings->grace_period }} menit toleransi)</span>
                    @endif
                </p>
                
                <div id="lateWarning" class="{{ $isLate ? '' : 'hidden' }} bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-3 mt-4 rounded">
                    <p class="font-semibold"><i class="fas fa-exclamation-triangle mr-2"></i>Anda Terlambat!</p>
                    <p class="text-xs mt-1">Jika memilih "Hadir", status akan tercatat sebagai TERLAMBAT</p>
                </div>
            </div>

            <!-- Data Guru (Read-only) -->
            <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Guru</label>
                        <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">NIY</label>
                        <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->niy }}</p>
                    </div>
                </div>
            </div>

            <!-- Status Kehadiran -->
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-3">Status Kehadiran <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-3 gap-3">
                    <label class="relative cursor-pointer">
                        <input type="radio" name="check_in_status" value="present" required class="peer sr-only" onchange="toggleReasonField()">
                        <div class="border-2 border-gray-300 rounded-lg p-4 text-center transition peer-checked:border-green-500 peer-checked:bg-green-50 hover:border-green-300">
                            <i class="fas fa-check-circle text-3xl mb-2 text-gray-400 peer-checked:text-green-600"></i>
                            <p class="text-sm font-semibold text-gray-700">Hadir</p>
                        </div>
                    </label>
                    
                    <label class="relative cursor-pointer">
                        <input type="radio" name="check_in_status" value="permission" required class="peer sr-only" onchange="toggleReasonField()">
                        <div class="border-2 border-gray-300 rounded-lg p-4 text-center transition peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:border-blue-300">
                            <i class="fas fa-file-alt text-3xl mb-2 text-gray-400 peer-checked:text-blue-600"></i>
                            <p class="text-sm font-semibold text-gray-700">Izin</p>
                        </div>
                    </label>
                    
                    <label class="relative cursor-pointer">
                        <input type="radio" name="check_in_status" value="sick" required class="peer sr-only" onchange="toggleReasonField()">
                        <div class="border-2 border-gray-300 rounded-lg p-4 text-center transition peer-checked:border-purple-500 peer-checked:bg-purple-50 hover:border-purple-300">
                            <i class="fas fa-notes-medical text-3xl mb-2 text-gray-400 peer-checked:text-purple-600"></i>
                            <p class="text-sm font-semibold text-gray-700">Sakit</p>
                        </div>
                    </label>
                </div>
                @error('check_in_status')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Alasan (untuk Izin/Sakit) -->
            <div id="reasonField" class="mb-6 hidden">
                <label class="block text-gray-700 font-semibold mb-2">Alasan <span class="text-red-500">*</span></label>
                <textarea 
                    name="check_in_reason" 
                    rows="3" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Jelaskan alasan izin/sakit Anda..."
                ></textarea>
                <p class="text-xs text-gray-500 mt-1">Wajib diisi untuk izin atau sakit</p>
                @error('check_in_reason')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Catatan Tambahan -->
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Catatan Tambahan (Opsional)</label>
                <textarea 
                    name="notes" 
                    rows="2" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Catatan tambahan..."
                ></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModal('modalCheckIn')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-semibold">
                    Batal
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold">
                    <i class="fas fa-check mr-2"></i>Konfirmasi Absensi
                </button>
            </div>
            </div>
        </form>
    </x-modal>

    @push('scripts')
    <script>
        function toggleReasonField() {
            const reasonField = document.getElementById('reasonField');
            const reasonTextarea = document.querySelector('textarea[name="check_in_reason"]');
            const selectedStatus = document.querySelector('input[name="check_in_status"]:checked');
            
            if (selectedStatus && (selectedStatus.value === 'permission' || selectedStatus.value === 'sick')) {
                reasonField.classList.remove('hidden');
                reasonTextarea.required = true;
            } else {
                reasonField.classList.add('hidden');
                reasonTextarea.required = false;
                reasonTextarea.value = '';
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Add change event to all radio buttons inside modal
            const statusRadios = document.querySelectorAll('#modalCheckIn input[name="check_in_status"]');
            
            statusRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    toggleReasonField();
                });
            });
            
            // Initial check
            toggleReasonField();
        });

        // Also trigger when modal opens
        window.addEventListener('openModal', function(e) {
            if (e.detail === 'modalCheckIn') {
                toggleReasonField();
            }
        });
    </script>
    @endpush

    <!-- Modal Check-out -->
    <x-modal id="modalCheckOut" title="🏠 Absensi Pulang" size="max-w-md">
        <form action="{{ route('guru.attendance.checkout') }}" method="POST">
            @csrf
            
            <div class="text-center mb-6">
                <div class="inline-block bg-purple-100 rounded-full p-4 mb-4">
                    <i class="fas fa-door-open text-4xl text-purple-600"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">{{ now()->format('H:i') }} WIB</h3>
                <p class="text-sm text-gray-600 mt-2">Waktu pulang kerja</p>
                
                <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-3 mt-4 rounded">
                    <p class="text-sm">Pastikan semua tugas hari ini sudah selesai</p>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Catatan (Opsional)</label>
                <textarea 
                    name="notes" 
                    rows="3" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                    placeholder="Tambahkan catatan jika diperlukan..."
                ></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModal('modalCheckOut')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-semibold">
                    Batal
                </button>
                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg font-semibold">
                    <i class="fas fa-check mr-2"></i>Konfirmasi Check-out
                </button>
            </div>
        </form>
    </x-modal>

    <!-- Modal Create Jurnal -->
    <x-modal id="modalCreateJurnal" title="Tambah Jurnal Mengajar" size="max-w-4xl">
        <form action="{{ route('guru.jurnal.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Tahun Ajaran <span class="text-red-500">*</span></label>
                    <select 
                        name="academic_year_id" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        required
                    >
                        <option value="">-- Pilih Tahun Ajaran --</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ ($activeAcademicYear && $activeAcademicYear->id == $year->id) ? 'selected' : '' }}>
                                {{ $year->full_name }} {{ $year->is_active ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="log_date" value="{{ old('log_date', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Mata Pelajaran <span class="text-red-500">*</span></label>
                    <select 
                        name="subject" 
                        id="dashboard_create_subject"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        required
                    >
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->name }}" {{ old('subject') == $subject->name ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-sm text-gray-500 mt-1">
                        Belum ada mata pelajaran? 
                        <a href="{{ route('guru.subjects.index') }}" target="_blank" class="text-blue-600 hover:underline">
                            <i class="fas fa-external-link-alt"></i> Kelola Mata Pelajaran
                        </a>
                    </p>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Kelas <span class="text-red-500">*</span></label>
                    <select name="class" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class }}">{{ $class }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Pertemuan Ke <span class="text-red-500">*</span></label>
                    <input type="number" name="meeting_number" value="{{ old('meeting_number', 1) }}" min="1" max="100" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Jam Pelajaran <span class="text-red-500">*</span></label>
                    <input type="text" name="time_slot" value="{{ old('time_slot') }}" placeholder="Contoh: 1-2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>
            <div class="mt-6">
                <label class="block text-gray-700 font-semibold mb-2">
                    Tujuan Pembelajaran (TP) <span class="text-red-500">*</span>
                </label>
                <select 
                    name="tp" 
                    id="dashboard_create_tp_select"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                    required
                    disabled
                >
                    <option value="">-- Pilih Mata Pelajaran Terlebih Dahulu --</option>
                </select>
                <p class="text-sm text-gray-500 mt-1">
                    Belum ada TP untuk mata pelajaran ini? 
                    <a href="{{ route('guru.tp.index') }}" target="_blank" class="text-blue-600 hover:underline">
                        <i class="fas fa-external-link-alt"></i> Kelola TP
                    </a>
                </p>
            </div>
            <div class="mt-6">
                <label class="block text-gray-700 font-semibold mb-2">Materi yang Diajarkan <span class="text-red-500">*</span></label>
                <textarea name="material" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>{{ old('material') }}</textarea>
            </div>
            <div class="mt-6">
                <label class="block text-gray-700 font-semibold mb-2">Catatan Tambahan</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('notes') }}</textarea>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal('modalCreateJurnal')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-semibold">Batal</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold"><i class="fas fa-save mr-2"></i>Simpan</button>
            </div>
        </form>
    </x-modal>

    <script>
        // Function untuk load TP berdasarkan mata pelajaran
        async function loadTPBySubject(subject, selectElementId, selectedValue = '') {
            const selectElement = document.getElementById(selectElementId);
            
            if (!subject) {
                selectElement.innerHTML = '<option value="">-- Pilih Mata Pelajaran Terlebih Dahulu --</option>';
                selectElement.disabled = true;
                return;
            }
            
            try {
                const response = await fetch(`/guru/tp/by-subject?subject=${encodeURIComponent(subject)}`);
                const tps = await response.json();
                
                selectElement.innerHTML = '<option value="">-- Pilih TP --</option>';
                
                if (tps.length === 0) {
                    selectElement.innerHTML += '<option value="" disabled>Belum ada TP untuk mata pelajaran ini</option>';
                } else {
                    tps.forEach(tp => {
                        const option = document.createElement('option');
                        option.value = tp.description;
                        option.textContent = tp.description;
                        if (selectedValue && tp.description === selectedValue) {
                            option.selected = true;
                        }
                        selectElement.appendChild(option);
                    });
                }
                
                selectElement.disabled = false;
            } catch (error) {
                console.error('Error loading TP:', error);
                selectElement.innerHTML = '<option value="">Error loading TP</option>';
                selectElement.disabled = true;
            }
        }
        
        // Event listener untuk form create - subject change
        document.addEventListener('DOMContentLoaded', function() {
            const dashboardCreateSubject = document.getElementById('dashboard_create_subject');
            if (dashboardCreateSubject) {
                dashboardCreateSubject.addEventListener('change', function() {
                    loadTPBySubject(this.value, 'dashboard_create_tp_select');
                });
            }
        });
    </script>
</x-layout>