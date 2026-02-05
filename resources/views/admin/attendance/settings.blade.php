<x-layout>
    <x-slot name="title">Pengaturan Absensi</x-slot>
    <x-slot name="header">Pengaturan Absensi</x-slot>

    <div class="max-w-4xl mx-auto">
        <!-- Info Card -->
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-600 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-semibold text-blue-800">Informasi Pengaturan</h3>
                    <p class="mt-1 text-sm text-blue-700">
                        Pengaturan ini akan mempengaruhi sistem absensi untuk semua guru. 
                        Pastikan untuk mengatur waktu dengan benar sesuai dengan kebijakan sekolah.
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <form action="{{ route('admin.attendance.settings.update') }}" method="POST">
                @csrf
                @method('PUT')
                @method('PUT')

                <!-- Current Settings Display -->
                @if($settings ?? null)
                <div class="bg-gradient-to-r from-purple-100 to-blue-100 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-cog mr-2 text-purple-600"></i>
                        Pengaturan Saat Ini
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-white rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">Jam Masuk</p>
                            <p class="text-2xl font-bold text-purple-700">{{ $settings->formatted_check_in_time }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">Batas Terlambat</p>
                            <p class="text-2xl font-bold text-orange-700">{{ $settings->formatted_late_time }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">Jam Pulang</p>
                            <p class="text-2xl font-bold text-green-700">{{ $settings->formatted_check_out_time }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">Toleransi</p>
                            <p class="text-2xl font-bold text-blue-700">{{ $settings->grace_period }} menit</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Time Settings -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-clock mr-2 text-blue-600"></i>
                        Pengaturan Waktu
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Check-in Time -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Jam Masuk Normal
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="time"
                                    id="work_start" 
                                   name="work_start" 
                                  value="{{ old('work_start', $settings->formatted_check_in_time ?? '07:00') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-lg font-semibold"
                                   required>
                            <p class="mt-1 text-xs text-gray-500">Waktu standar guru harus check-in</p>
                            @error('work_start')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Late Time -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Batas Waktu Terlambat
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="time"
                                      id="late_time" 
                                   name="late_time" 
                                   value="{{ old('late_time', $settings->formatted_late_time ?? '07:15') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-lg font-semibold"
                                   required>
                            <p class="mt-1 text-xs text-gray-500">Setelah waktu ini, status akan menjadi terlambat</p>
                            @error('late_time')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Grace Period -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Masa Toleransi (menit)
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                    id="grace_period"
                                   name="grace_period" 
                                   value="{{ old('grace_period', $settings->grace_period ?? 5) }}"
                                   min="0"
                                   max="30"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-lg font-semibold"
                                   required>
                            <p class="mt-1 text-xs text-gray-500">Tambahan waktu sebelum dianggap terlambat (0-30 menit)</p>
                            @error('grace_period')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Check-out Time -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Jam Pulang Normal
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="time"
                                    id="work_end" 
                                   name="work_end" 
                                  value="{{ old('work_end', $settings->formatted_check_out_time ?? '16:00') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-lg font-semibold"
                                   required>
                            <p class="mt-1 text-xs text-gray-500">Waktu standar guru check-out</p>
                            @error('work_end')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Working Days Per Month -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Jumlah Hari Kerja per Bulan
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                    id="working_days_per_month"
                                   name="working_days_per_month" 
                                   value="{{ old('working_days_per_month', $settings->working_days_per_month ?? 22) }}"
                                   min="1"
                                   max="31"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-lg font-semibold"
                                   required>
                            <p class="mt-1 text-xs text-gray-500">Jumlah hari kerja efektif dalam satu bulan (1-31 hari)</p>
                            @error('working_days_per_month')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Additional Settings -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-sliders-h mr-2 text-orange-600"></i>
                        Pengaturan Tambahan
                    </h3>
                    
                    <div class="space-y-4">
                        <!-- Allow Early Check-in -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <label class="font-semibold text-gray-800">Izinkan Check-in Lebih Awal</label>
                                <p class="text-sm text-gray-600 mt-1">Guru dapat melakukan check-in sebelum jam masuk normal</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       name="allow_early_checkin" 
                                       value="1"
                                       {{ old('allow_early_checkin', $settings->allow_early_checkin ?? true) ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <!-- Require Notes for Late -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <label class="font-semibold text-gray-800">Wajib Catatan untuk Terlambat</label>
                                <p class="text-sm text-gray-600 mt-1">Guru harus memberikan alasan saat check-in terlambat</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       name="require_late_notes" 
                                       value="1"
                                       {{ old('require_late_notes', $settings->require_late_notes ?? false) ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <!-- Auto Check-out -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <label class="font-semibold text-gray-800">Auto Check-out</label>
                                <p class="text-sm text-gray-600 mt-1">Otomatis check-out pada jam tertentu jika guru lupa</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       name="auto_checkout" 
                                       value="1"
                                       {{ old('auto_checkout', $settings->auto_checkout ?? false) ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pt-6 border-t">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-lg font-semibold transition">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                        <i class="fas fa-save mr-2"></i>Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>

        <!-- Preview Card -->
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl shadow-lg p-6 mt-6 text-white">
            <h3 class="text-lg font-bold mb-4 flex items-center">
                <i class="fas fa-eye mr-2"></i>
                Preview Pengaturan
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div class="bg-white/10 rounded-lg p-4">
                    <p class="text-white/80 mb-1">Jam Masuk</p>
                    <p class="text-xl font-bold" id="preview-checkin">
                        {{ $settings->formatted_check_in_time ?? '--:--' }}
                    </p>
                </div>
                <div class="bg-white/10 rounded-lg p-4">
                    <p class="text-white/80 mb-1">Batas Terlambat (+ toleransi)</p>
                    <p class="text-xl font-bold" id="preview-late">
                        {{ $settings->formatted_late_time ?? '--:--' }}
                    </p>

                </div>
                <div class="bg-white/10 rounded-lg p-4">
                    <p class="text-white/80 mb-1">Jam Pulang</p>
                    <p class="text-xl font-bold" id="preview-checkout">
                        {{ $settings->formatted_check_out_time ?? '--:--' }}
                    </p>
                </div>
                <div class="bg-white/10 rounded-lg p-4">
                    <p class="text-white/80 mb-1">Toleransi Keterlambatan</p>
                   <p class="text-xl font-bold" id="preview-grace">
                        {{ $settings->grace_period ?? 0 }} menit
                    </p>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {

        function updatePreview() {
            const checkin  = document.getElementById('work_start')?.value;
            const late     = document.getElementById('late_time')?.value;
            const checkout = document.getElementById('work_end')?.value;
            const grace    = parseInt(document.getElementById('grace_period')?.value) || 0;

            const previewCheckin = document.getElementById('preview-checkin');
            const previewCheckout = document.getElementById('preview-checkout');
            const previewLate = document.getElementById('preview-late');
            const previewGrace = document.getElementById('preview-grace');

            if (previewCheckin && checkin) previewCheckin.innerText = checkin;
            if (previewCheckout && checkout) previewCheckout.innerText = checkout;
            if (previewGrace) previewGrace.innerText = grace + ' menit';

            if (late && previewLate) {
                const [h, m] = late.split(':').map(Number);
                const total = h * 60 + m + grace;
                previewLate.innerText =
                    String(Math.floor(total / 60)).padStart(2, '0') + ':' +
                    String(total % 60).padStart(2, '0');
            }
        }

        document.querySelectorAll('input').forEach(el => {
            el.addEventListener('input', updatePreview);
            el.addEventListener('change', updatePreview);
        });

        updatePreview();
    });
    </script>
    @endpush
</x-layout>