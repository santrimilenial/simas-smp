<x-layout>
    <x-slot name="title">Buat Slip Gaji</x-slot>
    <x-slot name="header">Buat Slip Gaji Baru</x-slot>

    <div class="p-4 md:p-8">
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="mb-6">
                <a href="{{ route('bendahara.slip-gaji.index') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Slip Gaji
                </a>
            </div>

            <form action="{{ route('bendahara.slip-gaji.store') }}" method="POST">
                @csrf
                
                <!-- Period Selection -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Bulan <span class="text-red-500">*</span></label>
                        <select name="month" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($i)->isoFormat('MMMM') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun <span class="text-red-500">*</span></label>
                        <select name="year" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                            @for($i = now()->year; $i >= now()->year - 5; $i--)
                                <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Rate per Jam <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                            <input type="number" name="rate_per_hour" value="10000" min="0" step="500"
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        </div>
                    </div>
                </div>

                <!-- Quick Generate All Button -->
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-semibold text-green-800"><i class="fas fa-magic mr-2"></i>Generate Semua Sekaligus</h4>
                            <p class="text-sm text-green-600">Buat slip gaji untuk semua guru yang belum memiliki slip gaji pada periode ini</p>
                        </div>
                        <button type="submit" formaction="{{ route('bendahara.slip-gaji.generate-all') }}" 
                                class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-semibold transition">
                            <i class="fas fa-users mr-2"></i>Generate Semua
                        </button>
                    </div>
                </div>

                <!-- Teacher Selection -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <label class="block text-sm font-semibold text-gray-700">Pilih Guru <span class="text-red-500">*</span></label>
                        <div class="flex gap-2">
                            <button type="button" onclick="selectAll()" class="text-sm text-blue-600 hover:text-blue-800">
                                <i class="fas fa-check-square mr-1"></i>Pilih Semua
                            </button>
                            <button type="button" onclick="deselectAll()" class="text-sm text-gray-600 hover:text-gray-800">
                                <i class="fas fa-square mr-1"></i>Batal Pilih
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto border rounded-lg">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-center" style="width: 50px;">
                                        <input type="checkbox" id="selectAllCheckbox" onclick="toggleSelectAll(this)" 
                                               class="w-4 h-4 rounded border-gray-300 text-green-600 focus:ring-green-500">
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Nama Guru</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Jam Mengajar</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Estimasi Gaji</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($teachers as $teacher)
                                    @php
                                        $hasSlip = in_array($teacher->id, $existingSlips);
                                        $estimatedGaji = ($teacher->total_teaching_hours ?? 0) * 10000;
                                    @endphp
                                    <tr class="{{ $hasSlip ? 'bg-gray-100' : 'hover:bg-gray-50' }}">
                                        <td class="px-4 py-3 text-center">
                                            @if(!$hasSlip)
                                                <input type="checkbox" name="teacher_ids[]" value="{{ $teacher->id }}"
                                                       class="teacher-checkbox w-4 h-4 rounded border-gray-300 text-green-600 focus:ring-green-500">
                                            @else
                                                <i class="fas fa-check-circle text-green-500" title="Sudah ada slip gaji"></i>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8 bg-green-100 rounded-full flex items-center justify-center">
                                                    <span class="text-green-600 font-semibold text-sm">{{ substr($teacher->name, 0, 1) }}</span>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-semibold text-gray-900">{{ $teacher->name }}</p>
                                                    <p class="text-xs text-gray-500">{{ $teacher->niy }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center justify-center bg-purple-100 text-purple-800 px-3 py-1 rounded-lg font-bold text-sm">
                                                {{ $teacher->total_teaching_hours ?? 0 }} jam
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="text-sm font-semibold text-green-700">
                                                Rp {{ number_format($estimatedGaji, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @if($hasSlip)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                    <i class="fas fa-check mr-1"></i>Sudah Ada
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-clock mr-1"></i>Belum Ada
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end gap-4">
                    <a href="{{ route('bendahara.slip-gaji.index') }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-semibold transition">
                        Batal
                    </a>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-semibold transition">
                        <i class="fas fa-save mr-2"></i>Buat Slip Gaji Terpilih
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function selectAll() {
            document.querySelectorAll('.teacher-checkbox').forEach(checkbox => {
                checkbox.checked = true;
            });
            document.getElementById('selectAllCheckbox').checked = true;
        }

        function deselectAll() {
            document.querySelectorAll('.teacher-checkbox').forEach(checkbox => {
                checkbox.checked = false;
            });
            document.getElementById('selectAllCheckbox').checked = false;
        }

        function toggleSelectAll(masterCheckbox) {
            document.querySelectorAll('.teacher-checkbox').forEach(checkbox => {
                checkbox.checked = masterCheckbox.checked;
            });
        }

        // Update master checkbox when individual checkboxes change
        document.querySelectorAll('.teacher-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const allCheckboxes = document.querySelectorAll('.teacher-checkbox');
                const checkedCheckboxes = document.querySelectorAll('.teacher-checkbox:checked');
                document.getElementById('selectAllCheckbox').checked = allCheckboxes.length === checkedCheckboxes.length;
            });
        });
    </script>
</x-layout>
