<x-layout>
    <x-slot name="title">Laporan Absensi Harian</x-slot>
    <x-slot name="header">Laporan Absensi Harian</x-slot>

    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <!-- Filter Section -->
        <form method="GET" action="{{ route('admin.attendance.daily') }}" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal</label>
                    <input type="date" 
                           name="date" 
                           value="{{ request('date', now()->format('Y-m-d')) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Status</option>
                        <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Hadir Tepat Waktu</option>
                        <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Hadir Terlambat</option>
                        <option value="permission" {{ request('status') == 'permission' ? 'selected' : '' }}>Izin</option>
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
                    <a href="{{ route('admin.attendance.daily') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg font-semibold transition">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </div>
        </form>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-lg border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Hadir</p>
                        <h3 class="text-2xl font-bold text-green-700">{{ $summary['present'] ?? 0 }}</h3>
                    </div>
                    <div class="bg-green-200 rounded-full p-3">
                        <i class="fas fa-check text-xl text-green-700"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-4 rounded-lg border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Terlambat</p>
                        <h3 class="text-2xl font-bold text-yellow-700">{{ $summary['late'] ?? 0 }}</h3>
                    </div>
                    <div class="bg-yellow-200 rounded-full p-3">
                        <i class="fas fa-clock text-xl text-yellow-700"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-lg border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Izin</p>
                        <h3 class="text-2xl font-bold text-blue-700">{{ $summary['permission'] ?? 0 }}</h3>
                    </div>
                    <div class="bg-blue-200 rounded-full p-3">
                        <i class="fas fa-file-alt text-xl text-blue-700"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-lg border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Sakit</p>
                        <h3 class="text-2xl font-bold text-purple-700">{{ $summary['sick'] ?? 0 }}</h3>
                    </div>
                    <div class="bg-purple-200 rounded-full p-3">
                        <i class="fas fa-notes-medical text-xl text-purple-700"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Export Buttons -->
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">
                Daftar Absensi - {{ \Carbon\Carbon::parse(request('date', now()))->isoFormat('dddd, D MMMM YYYY') }}
            </h3>
            <div class="flex gap-2">
                <a href="{{ route('admin.attendance.daily.pdf', request()->all()) }}" 
                   class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                    <i class="fas fa-file-pdf mr-2"></i>Export PDF
                </a>
            </div>
        </div>

        <!-- Attendance Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama Guru</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">NIY</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Check In</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Check Out</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Lokasi</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Catatan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($attendances as $index => $attendance)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $attendances->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span class="text-blue-600 font-semibold">{{ substr($attendance->user->name, 0, 1) }}</span>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-semibold text-gray-900">{{ $attendance->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $attendance->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $attendance->user->niy }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($attendance->check_in_time)
                                    <div class="flex flex-col">
                                        <span class="flex items-center font-semibold">
                                            <i class="fas fa-sign-in-alt mr-2 text-green-600"></i>
                                            {{ $attendance->formatted_check_in }}
                                        </span>
                                        <span class="text-xs mt-1">
                                            @if(in_array($attendance->status, ['present', 'late']))
                                                @php
                                                    $settings = \App\Models\AttendanceSetting::current();
                                                    $checkInTime = $attendance->check_in_time;
                                                    $lateTime = \Carbon\Carbon::parse($settings->actual_late_time ?? '07:00');
                                                    $isLate = $checkInTime->greaterThan($lateTime);
                                                @endphp
                                                @if($isLate)
                                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded">
                                                        Terlambat
                                                    </span>
                                                @else
                                                    <span class="bg-green-100 text-green-800 px-2 py-0.5 rounded">
                                                        Tepat Waktu
                                                    </span>
                                                @endif
                                            @endif
                                        </span>
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($attendance->check_out_time)
                                    <div class="flex flex-col">
                                        <span class="flex items-center font-semibold">
                                            <i class="fas fa-sign-out-alt mr-2 text-purple-600"></i>
                                            {{ $attendance->formatted_check_out }}
                                        </span>
                                        @if($attendance->check_out_status)
                                            <span class="text-xs text-gray-600 mt-1">
                                                <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded">
                                                    {{ $attendance->check_out_status_label }}
                                                </span>
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex flex-col gap-1">
                                    {{-- Lokasi Check In --}}
                                    @if($attendance->check_in_latitude && $attendance->check_in_longitude)
                                        <a href="{{ $attendance->check_in_map_url }}" 
                                           target="_blank"
                                           class="inline-flex items-center text-xs text-green-600 hover:text-green-800 hover:underline"
                                           title="Lokasi Check In: {{ $attendance->check_in_location }}">
                                            <i class="fas fa-map-marker-alt mr-1"></i>
                                            <span>Masuk</span>
                                            <i class="fas fa-external-link-alt ml-1 text-[10px]"></i>
                                        </a>
                                    @else
                                        @if($attendance->check_in_time)
                                            <span class="text-xs text-gray-400">
                                                <i class="fas fa-map-marker-alt mr-1"></i>Masuk: -
                                            </span>
                                        @endif
                                    @endif
                                    
                                    {{-- Lokasi Check Out --}}
                                    @if($attendance->check_out_latitude && $attendance->check_out_longitude)
                                        <a href="{{ $attendance->check_out_map_url }}" 
                                           target="_blank"
                                           class="inline-flex items-center text-xs text-purple-600 hover:text-purple-800 hover:underline"
                                           title="Lokasi Check Out: {{ $attendance->check_out_location }}">
                                            <i class="fas fa-map-marker-alt mr-1"></i>
                                            <span>Pulang</span>
                                            <i class="fas fa-external-link-alt ml-1 text-[10px]"></i>
                                        </a>
                                    @else
                                        @if($attendance->check_out_time)
                                            <span class="text-xs text-gray-400">
                                                <i class="fas fa-map-marker-alt mr-1"></i>Pulang: -
                                            </span>
                                        @endif
                                    @endif
                                    
                                    @if(!$attendance->check_in_latitude && !$attendance->check_out_latitude)
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($attendance->status === 'present') bg-green-100 text-green-800
                                    @elseif($attendance->status === 'late') bg-yellow-100 text-yellow-800
                                    @else bg-blue-100 text-blue-800
                                    @endif">
                                    {{ $attendance->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                @if(in_array($attendance->check_in_status, ['permission', 'sick']) && $attendance->check_in_reason)
                                    <div class="flex items-start gap-2">
                                        <span class="px-2 py-1 bg-blue-50 text-blue-800 rounded border border-blue-200 text-xs">
                                            <i class="fas fa-info-circle mr-1"></i>{{ $attendance->check_in_reason }}
                                        </span>
                                    </div>
                                @elseif($attendance->notes)
                                    <button onclick="showNotes('{{ addslashes($attendance->notes) }}')" 
                                            class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-comment-alt"></i> Lihat
                                    </button>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <i class="fas fa-inbox text-5xl mb-3"></i>
                                    <p class="text-lg font-semibold">Tidak ada data absensi</p>
                                    <p class="text-sm mt-1">Belum ada guru yang melakukan absensi pada tanggal ini</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($attendances->hasPages())
            <div class="mt-6">
                {{ $attendances->links() }}
            </div>
        @endif
    </div>

    <!-- Notes Modal -->
    <x-modal id="notesModal" title="Catatan Absensi" size="max-w-md">
        <div class="p-4">
            <div class="bg-gray-50 rounded-lg p-4">
                <p id="notesContent" class="text-gray-800"></p>
            </div>
            <div class="mt-4 flex justify-end">
                <button onclick="closeModal('notesModal')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-semibold">
                    Tutup
                </button>
            </div>
        </div>
    </x-modal>

    @push('scripts')
    <script>
        function showNotes(notes) {
            document.getElementById('notesContent').textContent = notes;
            openModal('notesModal');
        }
    </script>
    @endpush
</x-layout>