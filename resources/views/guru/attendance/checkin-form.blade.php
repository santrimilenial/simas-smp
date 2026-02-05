@php
    $todayAttendance = auth()->user()->todayAttendance();
@endphp

<x-layout>
    <x-slot name="title">Form Absen Berangkat</x-slot>
    <x-slot name="header">Form Absen Berangkat</x-slot>

    <!-- Script untuk toggle reason field -->
    <script>
        function updateFormDisplay() {
            var reasonField = document.getElementById('reasonField');
            var reasonInput = document.getElementById('check_in_reason');
            var teachingHoursField = document.getElementById('teachingHoursField');
            var teachingHoursInput = document.getElementById('teaching_hours');
            var statusInfo = document.getElementById('statusInfo');
            
            if (!reasonField || !statusInfo || !teachingHoursField) {
                return;
            }
            
            var selectedStatus = null;
            var statusRadios = document.querySelectorAll('input[name="check_in_status"]');
            for (var i = 0; i < statusRadios.length; i++) {
                if (statusRadios[i].checked) {
                    selectedStatus = statusRadios[i].value;
                    break;
                }
            }
            
            if (selectedStatus === 'permission') {
                reasonField.style.cssText = 'display: block !important;';
                teachingHoursField.style.cssText = 'display: none !important;';
                if (reasonInput) reasonInput.required = true;
                if (teachingHoursInput) teachingHoursInput.required = false;
                statusInfo.innerHTML = '<p class="text-blue-700 font-semibold text-sm"><i class="fas fa-info-circle mr-2"></i>Mohon jelaskan alasan izin Anda</p>';
                statusInfo.className = 'p-4 rounded-lg border-l-4 bg-blue-50 border-blue-500';
            } else if (selectedStatus === 'sick') {
                reasonField.style.cssText = 'display: block !important;';
                teachingHoursField.style.cssText = 'display: none !important;';
                if (reasonInput) reasonInput.required = true;
                if (teachingHoursInput) teachingHoursInput.required = false;
                statusInfo.innerHTML = '<p class="text-purple-700 font-semibold text-sm"><i class="fas fa-info-circle mr-2"></i>Mohon jelaskan kondisi kesehatan Anda</p>';
                statusInfo.className = 'p-4 rounded-lg border-l-4 bg-purple-50 border-purple-500';
            } else {
                reasonField.style.cssText = 'display: none !important;';
                teachingHoursField.style.cssText = 'display: block !important;';
                if (reasonInput) reasonInput.required = false;
                if (teachingHoursInput) teachingHoursInput.required = false;
                statusInfo.innerHTML = '<p class="text-green-700 font-semibold text-sm"><i class="fas fa-check-circle mr-2"></i>Anda hadir hari ini</p>';
                statusInfo.className = 'p-4 rounded-lg border-l-4 bg-green-50 border-green-500';
            }
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            updateFormDisplay();
        });
    </script>

    <div class="max-w-2xl mx-auto px-4 py-4">
        <!-- Header Info -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl p-4 md:p-6 mb-6 shadow-lg">
            <h1 class="text-xl md:text-2xl font-bold mb-2">Absen Berangkat</h1>
            <p class="text-xs md:text-sm opacity-90">{{ now()->isoFormat('dddd, D MMMM YYYY') }}</p>
            <div class="mt-4 text-2xl md:text-3xl font-bold" id="clock">{{ now()->format('H:i:s') }}</div>
        </div>

        <!-- Form Absen Berangkat -->
        <div class="bg-white rounded-xl shadow-lg p-4 md:p-8 mb-6">
            @if($todayAttendance && $todayAttendance->check_in_time)
                <!-- Sudah Check-In -->
                <div class="bg-green-50 border-l-4 border-green-500 p-6 rounded mb-6">
                    <p class="text-green-800 font-semibold text-lg mb-3">✅ Anda sudah Absen Berangkat</p>
                    
                    <div class="bg-white p-4 rounded-lg space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 font-semibold">Waktu:</span>
                            <span class="text-gray-900 font-bold text-lg">{{ $todayAttendance->formatted_check_in }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 font-semibold">Status:</span>
                            <span class="text-gray-900">{{ $todayAttendance->check_in_status_label }}</span>
                        </div>

                        @if($todayAttendance->check_in_status === 'present')
                            <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                                <span class="text-gray-700 font-semibold">Ketepatan Waktu:</span>
                                <div>
                                    @php
                                        $settings = \App\Models\AttendanceSetting::current();
                                        $checkInTime = $todayAttendance->check_in_time;
                                        $lateTime = \Carbon\Carbon::parse($settings->actual_late_time ?? '07:00');
                                        $isLate = $checkInTime->greaterThan($lateTime);
                                    @endphp
                                    @if($isLate)
                                        <span class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full font-semibold">
                                            ⏱️ Terlambat
                                        </span>
                                    @else
                                        <span class="bg-green-100 text-green-800 px-4 py-2 rounded-full font-semibold">
                                            ✅ Tepat Waktu
                                        </span>
                                    @endif
                                </div>
                            </div>

                            @if($todayAttendance->teaching_hours)
                                <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                                    <span class="text-gray-700 font-semibold">
                                        <i class="fas fa-chalkboard-teacher text-blue-600 mr-2"></i>Jumlah Jam Mengajar:
                                    </span>
                                    <span class="bg-blue-100 text-blue-800 px-4 py-2 rounded-full font-semibold">
                                        {{ $todayAttendance->teaching_hours }} Jam Pelajaran
                                    </span>
                                </div>
                            @endif
                        @endif
                        
                        @if($todayAttendance->check_in_reason)
                            <div class="pt-3 border-t border-gray-200">
                                <p class="text-gray-700 font-semibold mb-2">Keterangan:</p>
                                <p class="text-gray-900 bg-gray-50 p-3 rounded">{{ $todayAttendance->check_in_reason }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <form method="POST" action="{{ route('guru.attendance.checkin') }}" id="checkinForm" class="space-y-6">
                    @csrf
                    <input type="hidden" name="type" value="check_in">
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">

                    <!-- Location Status -->
                    <div id="locationStatus" class="bg-gray-50 border-l-4 border-gray-400 p-4 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-spinner fa-spin text-gray-500 mr-3"></i>
                            <div>
                                <p class="text-gray-700 font-semibold text-sm">Mengambil lokasi...</p>
                                <p class="text-gray-500 text-xs">Mohon izinkan akses lokasi di browser Anda</p>
                            </div>
                        </div>
                    </div>

                    <!-- Error Messages -->
                    @if($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 p-3 md:p-4 rounded">
                            <h4 class="text-red-800 font-semibold mb-2 text-sm md:text-base">❌ Ada Kesalahan:</h4>
                            <ul class="text-red-700 text-xs md:text-sm space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Work Time Info -->
                    @php
                        $settings = \App\Models\AttendanceSetting::current();
                        $currentTime = now();
                        $workEndTime = \Carbon\Carbon::parse($settings->work_end);
                        $isAfterWorkEnd = $currentTime->format('H:i:s') > $workEndTime->format('H:i:s');
                    @endphp
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-clock text-blue-600 text-xl mr-3 mt-1"></i>
                            <div class="flex-1">
                                <h4 class="font-semibold text-blue-800 mb-2">⏰ Jadwal Kerja</h4>
                                <div class="grid grid-cols-2 gap-3 text-sm text-blue-700">
                                    <div>
                                        <p class="text-xs text-blue-600">Jam Masuk</p>
                                        <p class="font-bold">{{ $settings->formatted_check_in_time }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-blue-600">Jam Pulang</p>
                                        <p class="font-bold">{{ $settings->formatted_check_out_time }}</p>
                                    </div>
                                </div>
                                @if($isAfterWorkEnd)
                                    <p class="text-xs text-red-600 mt-2 font-semibold">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        Waktu absen berangkat telah berakhir
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Status Selection -->
                    <div>
                        <label class="block text-base md:text-lg font-semibold text-gray-800 mb-3 md:mb-4">Status Kehadiran</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 md:gap-3">
                            <label class="flex items-center p-3 md:p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-400 transition" onclick="updateFormDisplay()">
                                <input type="radio" name="check_in_status" value="present" class="w-4 h-4 text-blue-600" onchange="updateFormDisplay()" @checked(old('check_in_status') === 'present')>
                                <span class="ml-2 md:ml-3 font-semibold text-gray-700 text-sm md:text-base">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>Hadir
                                </span>
                            </label>
                            <label class="flex items-center p-3 md:p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-yellow-400 transition" onclick="updateFormDisplay()">
                                <input type="radio" name="check_in_status" value="permission" class="w-4 h-4 text-yellow-600" onchange="updateFormDisplay()" @checked(old('check_in_status') === 'permission')>
                                <span class="ml-2 md:ml-3 font-semibold text-gray-700 text-sm md:text-base">
                                    <i class="fas fa-clipboard-list text-yellow-500 mr-2"></i>Izin
                                </span>
                            </label>
                            <label class="flex items-center p-3 md:p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-red-400 transition" onclick="updateFormDisplay()">
                                <input type="radio" name="check_in_status" value="sick" class="w-4 h-4 text-red-600" onchange="updateFormDisplay()" @checked(old('check_in_status') === 'sick')>
                                <span class="ml-2 md:ml-3 font-semibold text-gray-700 text-sm md:text-base">
                                    <i class="fas fa-hospital-user text-red-500 mr-2"></i>Sakit
                                </span>
                            </label>
                        </div>
                        @error('check_in_status')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status Info Box -->
                    <div id="statusInfo" class="p-4 rounded-lg text-white bg-green-50 border-l-4 border-green-500">
                        <p id="statusMessage" class="font-semibold text-green-700 text-sm"><i class="fas fa-check-circle mr-2"></i>Anda hadir hari ini</p>
                    </div>

                    <!-- Reason Field (Izin/Sakit) -->
                    <div id="reasonField" style="display: none;">
                        <label class="block text-base md:text-lg font-semibold text-gray-800 mb-2 md:mb-3">Keterangan <span class="text-red-500">*</span></label>
                        <textarea 
                            id="check_in_reason"
                            name="check_in_reason" 
                            placeholder="Jelaskan alasan izin atau sakit Anda..."
                            class="w-full p-3 md:p-4 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition text-sm md:text-base"
                            rows="4"
                        >{{ old('check_in_reason') }}</textarea>
                        @error('check_in_reason')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Teaching Hours Field (Hadir/Terlambat) -->
                    <div id="teachingHoursField" style="display: none;">
                        <label class="block text-base md:text-lg font-semibold text-gray-800 mb-2 md:mb-3">
                            <i class="fas fa-chalkboard-teacher text-blue-600 mr-2"></i>Jumlah Jam Mengajar Hari Ini <span class="text-gray-400 text-xs md:text-sm">(Opsional)</span>
                        </label>
                        <div class="relative">
                            <input 
                                id="teaching_hours"
                                type="number" 
                                name="teaching_hours" 
                                value="{{ old('teaching_hours') }}"
                                placeholder="Contoh: 5 (jika mengajar 5 jam pelajaran)"
                                min="1"
                                max="12"
                                class="w-full p-3 md:p-4 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition text-sm md:text-base"
                            >
                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 font-semibold">
                                Jam
                            </div>
                        </div>
                        <p class="text-xs md:text-sm text-gray-600 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>Isi berapa jam Anda mengajar pada hari ini. Contoh: jika mengajar 5 jam pelajaran, isi dengan angka 5
                        </p>
                        @error('teaching_hours')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes Field (Optional) -->
                    <div>
                        <label class="block text-base md:text-lg font-semibold text-gray-800 mb-2 md:mb-3">Catatan Tambahan <span class="text-gray-400 text-xs md:text-sm">(Opsional)</span></label>
                        <textarea 
                            name="notes" 
                            placeholder="Catatan tambahan jika ada..."
                            class="w-full p-3 md:p-4 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition text-sm md:text-base"
                            rows="3"
                        ></textarea>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-3 md:py-4 rounded-lg transition shadow-lg text-sm md:text-base">
                        <i class="fas fa-paper-plane mr-2"></i>Simpan Absen Berangkat
                    </button>
                </form>
            @endif
        </div>

        <!-- Back to Dashboard -->
        <a href="{{ route('guru.dashboard') }}" class="inline-block bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 md:py-3 px-4 md:px-6 rounded-lg transition text-sm md:text-base">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Dashboard
        </a>
    </div>

    @push('scripts')
    <script>
        // Update clock
        setInterval(() => {
            const now = new Date();
            document.getElementById('clock').textContent = now.toLocaleTimeString('id-ID');
        }, 1000);

        // Update form display based on status
        function updateFormDisplay() {
            const status = document.querySelector('input[name="check_in_status"]:checked');
            const reasonField = document.getElementById('reasonField');
            const teachingHoursField = document.getElementById('teachingHoursField');
            const statusInfo = document.getElementById('statusInfo');
            const statusMessage = document.getElementById('statusMessage');

            if (!status) {
                if (reasonField) reasonField.style.display = 'none';
                if (teachingHoursField) teachingHoursField.style.display = 'none';
                if (statusInfo) statusInfo.classList.add('hidden');
                return;
            }

            const statusValue = status.value;
            if (statusInfo) statusInfo.classList.remove('hidden');

            if (statusValue === 'present') {
                if (statusInfo) {
                    statusInfo.className = 'p-4 rounded-lg border-l-4 bg-green-50 border-green-500';
                    statusMessage.innerHTML = '<p class="text-green-700 font-semibold text-sm"><i class="fas fa-check-circle mr-2"></i>Anda hadir hari ini</p>';
                }
                if (reasonField) reasonField.style.display = 'none';
                if (teachingHoursField) teachingHoursField.style.display = 'block';
                
                const reasonInput = document.getElementById('check_in_reason');
                const teachingHoursInput = document.getElementById('teaching_hours');
                if (reasonInput) reasonInput.required = false;
                if (teachingHoursInput) teachingHoursInput.required = false;
            } else if (statusValue === 'permission') {
                if (statusInfo) {
                    statusInfo.className = 'p-4 rounded-lg border-l-4 bg-blue-50 border-blue-500';
                    statusMessage.innerHTML = '<p class="text-blue-700 font-semibold text-sm"><i class="fas fa-info-circle mr-2"></i>Mohon jelaskan alasan izin Anda</p>';
                }
                if (reasonField) reasonField.style.display = 'block';
                if (teachingHoursField) teachingHoursField.style.display = 'none';
                
                const reasonInput = document.getElementById('check_in_reason');
                const teachingHoursInput = document.getElementById('teaching_hours');
                if (reasonInput) reasonInput.required = true;
                if (teachingHoursInput) teachingHoursInput.required = false;
            } else if (statusValue === 'sick') {
                if (statusInfo) {
                    statusInfo.className = 'p-4 rounded-lg border-l-4 bg-purple-50 border-purple-500';
                    statusMessage.innerHTML = '<p class="text-purple-700 font-semibold text-sm"><i class="fas fa-info-circle mr-2"></i>Mohon jelaskan kondisi kesehatan Anda</p>';
                }
                if (reasonField) reasonField.style.display = 'block';
                if (teachingHoursField) teachingHoursField.style.display = 'none';
                
                const reasonInput = document.getElementById('check_in_reason');
                const teachingHoursInput = document.getElementById('teaching_hours');
                if (reasonInput) reasonInput.required = true;
                if (teachingHoursInput) teachingHoursInput.required = false;
            }

        }

        // Initialize form on page load
        document.addEventListener('DOMContentLoaded', function() {
            // If there are any checked status radios on page load, show the form
            const checkedStatus = document.querySelector('input[name="check_in_status"]:checked');
            if (checkedStatus) {
                updateFormDisplay();
            }

            // Setup radio button listeners
            const statusRadios = document.querySelectorAll('input[name="check_in_status"]');
            statusRadios.forEach(radio => {
                radio.addEventListener('change', updateFormDisplay);
            });

            // Form submission validation
            const checkinForm = document.getElementById('checkinForm');
            if (checkinForm) {
                checkinForm.addEventListener('submit', function(e) {
                    const status = document.querySelector('input[name="check_in_status"]:checked');

                    if (!status) {
                        e.preventDefault();
                        alert('Pilih status kehadiran terlebih dahulu');
                        return false;
                    }
                });
            }

            // Get GPS Location
            getLocation();
        });

        function getLocation() {
            const locationStatus = document.getElementById('locationStatus');
            
            if (!navigator.geolocation) {
                locationStatus.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-yellow-500 mr-3"></i>
                        <div>
                            <p class="text-yellow-700 font-semibold text-sm">Browser tidak mendukung GPS</p>
                            <p class="text-yellow-600 text-xs">Lokasi tidak dapat direkam</p>
                        </div>
                    </div>
                `;
                locationStatus.className = 'bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg';
                return;
            }

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    const accuracy = position.coords.accuracy; // dalam meter
                    
                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lng;
                    
                    // Tentukan warna berdasarkan akurasi
                    let accuracyColor = 'green';
                    let accuracyText = 'Sangat Akurat';
                    let accuracyIcon = 'fa-check-circle';
                    
                    if (accuracy > 100) {
                        accuracyColor = 'red';
                        accuracyText = 'Kurang Akurat';
                        accuracyIcon = 'fa-exclamation-triangle';
                    } else if (accuracy > 50) {
                        accuracyColor = 'yellow';
                        accuracyText = 'Cukup Akurat';
                        accuracyIcon = 'fa-exclamation-circle';
                    }
                    
                    locationStatus.innerHTML = `
                        <div class="flex flex-col gap-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt text-green-500 mr-3 text-xl"></i>
                                    <div>
                                        <p class="text-green-700 font-semibold text-sm">Lokasi berhasil diambil</p>
                                        <p class="text-green-600 text-xs">${lat.toFixed(6)}, ${lng.toFixed(6)}</p>
                                    </div>
                                </div>
                                <a href="https://www.google.com/maps?q=${lat},${lng}" target="_blank" 
                                   class="text-green-600 hover:text-green-800 text-xs underline">
                                    <i class="fas fa-external-link-alt mr-1"></i>Lihat Map
                                </a>
                            </div>
                            <div class="flex items-center justify-between text-xs border-t border-green-200 pt-2 mt-1">
                                <span class="flex items-center text-${accuracyColor}-600">
                                    <i class="fas ${accuracyIcon} mr-1"></i>
                                    ${accuracyText} (± ${Math.round(accuracy)} meter)
                                </span>
                                <button type="button" onclick="getLocation()" class="text-blue-600 hover:text-blue-800 underline">
                                    <i class="fas fa-sync-alt mr-1"></i>Perbarui Lokasi
                                </button>
                            </div>
                        </div>
                    `;
                    locationStatus.className = 'bg-green-50 border-l-4 border-green-500 p-4 rounded-lg';
                },
                function(error) {
                    let errorMsg = 'Gagal mengambil lokasi';
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMsg = 'Akses lokasi ditolak. Mohon izinkan akses lokasi.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMsg = 'Informasi lokasi tidak tersedia';
                            break;
                        case error.TIMEOUT:
                            errorMsg = 'Waktu pengambilan lokasi habis. Coba lagi.';
                            break;
                    }
                    
                    locationStatus.innerHTML = `
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle text-orange-500 mr-3"></i>
                                <div>
                                    <p class="text-orange-700 font-semibold text-sm">${errorMsg}</p>
                                    <p class="text-orange-600 text-xs">Anda tetap bisa absen tanpa lokasi</p>
                                </div>
                            </div>
                            <button type="button" onclick="getLocation()" class="text-orange-600 hover:text-orange-800 text-xs underline">
                                <i class="fas fa-redo mr-1"></i>Coba Lagi
                            </button>
                        </div>
                    `;
                    locationStatus.className = 'bg-orange-50 border-l-4 border-orange-400 p-4 rounded-lg';
                },
                {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 0
                }
            );
        }
    </script>
    @endpush
</x-layout>
