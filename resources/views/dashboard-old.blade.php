<x-layout>
    <x-slot name="title">Dashboard Guru</x-slot>
    <x-slot name="header">Dashboard Guru</x-slot>

    <!-- Greeting & Absensi Card -->
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl shadow-lg p-6 mb-8 text-white">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex-1">
                <h2 class="text-2xl font-bold">Selamat Datang, {{ auth()->user()->name }}! 👋</h2>
                <p class="mt-2">NIP: {{ auth()->user()->nip }}</p>
                <p class="text-sm opacity-90 mt-1">{{ now()->isoFormat('dddd, D MMMM YYYY') }} • {{ now()->format('H:i') }} WIB</p>
            </div>
            <div class="flex gap-3">
                @php
                    $todayAttendance = auth()->user()->todayAttendance();
                @endphp
                
                @if(!$todayAttendance)
                    <a href="{{ route('guru.attendance.checkin.form') }}" class="bg-white text-blue-600 hover:bg-blue-50 px-6 py-3 rounded-lg font-semibold shadow-lg transition">
                        <i class="fas fa-sign-in-alt mr-2"></i>Absen Masuk
                    </a>
                @elseif(!$todayAttendance->check_out_time && $todayAttendance->status === 'present')
                    <div class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-lg">
                        <p class="text-sm">Check-in: <strong>{{ $todayAttendance->formatted_check_in }}</strong></p>
                        <p class="text-xs">Status: <span class="font-semibold">{{ $todayAttendance->status_label }}</span></p>
                    </div>
                @else
                    <div class="bg-white/20 backdrop-blur-sm px-6 py-3 rounded-lg text-center">
                        <p class="text-sm font-semibold">✅ Status: {{ $todayAttendance->status_label }}</p>
                        <p class="text-xs mt-1">{{ $todayAttendance->formatted_check_in ?? 'Sudah tercatat' }}</p>
                    </div>
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

    <!-- Quick Actions & Recent Logs -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-bolt mr-2 text-yellow-500"></i>Quick Actions
            </h3>
            <div class="space-y-3">
                <a href="{{ route('guru.jurnal.create') }}" class="block bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white p-4 rounded-lg text-center font-semibold transition">
                    <i class="fas fa-plus-circle mr-2"></i>Tambah Jurnal Baru
                </a>
                <a href="{{ route('guru.jurnal.index') }}" class="block bg-gray-100 hover:bg-gray-200 text-gray-800 p-4 rounded-lg text-center font-semibold transition">
                    <i class="fas fa-list mr-2"></i>Lihat Semua Jurnal
                </a>
                <a href="{{ route('guru.reports.index') }}" class="block bg-gray-100 hover:bg-gray-200 text-gray-800 p-4 rounded-lg text-center font-semibold transition">
                    <i class="fas fa-file-download mr-2"></i>Download Laporan
                </a>
            </div>
        </div>

        <!-- Recent Logs -->
        <div class="bg-white rounded-xl shadow-sm p-6 lg:col-span-2">
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

    <!-- Modal Check-in -->
    <x-modal id="modalCheckIn" title="📅 Absensi Masuk" size="max-w-md">
        <form action="{{ route('guru.attendance.checkin') }}" method="POST" id="checkInForm">
            @csrf
            @php
                $settings = \App\Models\AttendanceSetting::current();
                $isLate = $settings->isLate(now());
            @endphp
            
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    <p class="font-semibold">Validasi Gagal:</p>
                    <ul class="list-disc ml-5 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

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
            </div>

            <!-- Status Selection -->
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-3">Status Kehadiran <span class="text-red-500">*</span></label>
                <div class="space-y-2">
                    <div class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-blue-50 transition" onclick="selectStatus('present')">
                        <input type="radio" id="statusPresent" name="status" value="present" class="mr-3" checked onchange="updateStatusInfo('present')">
                        <label for="statusPresent" class="font-semibold text-gray-700 cursor-pointer flex-1">Hadir</label>
                    </div>
                    <div class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-blue-50 transition" onclick="selectStatus('permission')">
                        <input type="radio" id="statusPermission" name="status" value="permission" class="mr-3" onchange="updateStatusInfo('permission')">
                        <label for="statusPermission" class="font-semibold text-gray-700 cursor-pointer flex-1">Izin</label>
                    </div>
                    <div class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-blue-50 transition" onclick="selectStatus('sick')">
                        <input type="radio" id="statusSick" name="status" value="sick" class="mr-3" onchange="updateStatusInfo('sick')">
                        <label for="statusSick" class="font-semibold text-gray-700 cursor-pointer flex-1">Sakit</label>
                    </div>
                </div>
                @error('status')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status Info Alert -->
            <div id="statusAlert" class="mb-6">
                @if($isLate)
                    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-3 rounded">
                        <p class="font-semibold"><i class="fas fa-exclamation-triangle mr-2"></i>Anda Terlambat!</p>
                        <p class="text-xs mt-1">Absensi akan tercatat sebagai TERLAMBAT</p>
                    </div>
                @else
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 rounded">
                        <p class="font-semibold"><i class="fas fa-check-circle mr-2"></i>Tepat Waktu</p>
                        <p class="text-xs mt-1">Anda datang tepat waktu</p>
                    </div>
                @endif
            </div>

            <!-- Reason Field (for permission/sick) -->
            <div id="reasonField" class="mb-6 hidden">
                <label class="block text-gray-700 font-semibold mb-2">
                    Alasan <span class="text-red-500">*</span>
                </label>
                <textarea 
                    id="reasonInput"
                    name="reason" 
                    rows="3" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Jelaskan alasan izin atau sakit Anda..."
                ></textarea>
                @error('reason')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes Field -->
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Catatan (Opsional)</label>
                <textarea 
                    name="notes" 
                    rows="2" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Tambahkan catatan jika diperlukan..."
                ></textarea>
                @error('notes')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModal('modalCheckIn')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-semibold">
                    Batal
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold">
                    <i class="fas fa-check mr-2"></i>Konfirmasi Check-in
                </button>
            </div>
        </form>
    </x-modal>

    <script>
        function selectStatus(status) {
            const radios = document.querySelectorAll('input[name="status"]');
            radios.forEach(radio => {
                if (radio.value === status) {
                    radio.checked = true;
                }
            });
            updateStatusInfo(status);
        }

        function updateStatusInfo(status) {
            const statusAlert = document.getElementById('statusAlert');
            const reasonField = document.getElementById('reasonField');
            const reasonInput = document.getElementById('reasonInput');
            
            if (status === 'present') {
                // Update status alert
                @if($isLate)
                    statusAlert.innerHTML = '<div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-3 rounded"><p class="font-semibold"><i class="fas fa-exclamation-triangle mr-2"></i>Anda Terlambat!</p><p class="text-xs mt-1">Absensi akan tercatat sebagai TERLAMBAT</p></div>';
                @else
                    statusAlert.innerHTML = '<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 rounded"><p class="font-semibold"><i class="fas fa-check-circle mr-2"></i>Tepat Waktu</p><p class="text-xs mt-1">Anda datang tepat waktu</p></div>';
                @endif
                reasonField.classList.add('hidden');
                reasonInput.removeAttribute('required');
                reasonInput.value = '';
            } else if (status === 'permission') {
                statusAlert.innerHTML = '<div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-3 rounded"><p class="font-semibold"><i class="fas fa-file-alt mr-2"></i>Izin</p><p class="text-xs mt-1">Mohon sertakan alasan izin Anda</p></div>';
                reasonField.classList.remove('hidden');
                reasonInput.setAttribute('required', 'required');
            } else if (status === 'sick') {
                statusAlert.innerHTML = '<div class="bg-purple-100 border-l-4 border-purple-500 text-purple-700 p-3 rounded"><p class="font-semibold"><i class="fas fa-heartbeat mr-2"></i>Sakit</p><p class="text-xs mt-1">Mohon sertakan informasi sakit Anda</p></div>';
                reasonField.classList.remove('hidden');
                reasonInput.setAttribute('required', 'required');
            }
        }

        // Form validation
        document.getElementById('checkInForm').addEventListener('submit', function(e) {
            const status = document.querySelector('input[name="status"]:checked').value;
            const reason = document.getElementById('reasonInput').value.trim();
            
            if ((status === 'permission' || status === 'sick') && !reason) {
                e.preventDefault();
                alert('Alasan harus diisi untuk status izin atau sakit');
                document.getElementById('reasonInput').focus();
                return false;
            }
        });
    </script>

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
</x-layout>