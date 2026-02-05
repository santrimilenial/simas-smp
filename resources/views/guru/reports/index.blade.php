<x-layout>
    <x-slot name="title">Laporan Jurnal</x-slot>
    <x-slot name="header">Laporan Jurnal Saya</x-slot>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <!-- Filter & Export -->
        <form method="GET" action="{{ route('guru.reports.index') }}" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Mulai</label>
                    <input 
                        type="date" 
                        name="start_date"
                        value="{{ $startDate }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Akhir</label>
                    <input 
                        type="date" 
                        name="end_date"
                        value="{{ $endDate }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Filter Kelas</label>
                    <select 
                        name="class"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="">Semua Kelas</option>
                        @foreach($classes as $class)
                            <option value="{{ $class }}" {{ $filterClass == $class ? 'selected' : '' }}>
                                {{ $class }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                </div>
            </div>
        </form>

        <!-- Export Buttons -->
        <div class="flex gap-3 mb-6">
            <a href="{{ route('guru.reports.pdf', request()->query()) }}" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-semibold">
                <i class="fas fa-file-pdf mr-2"></i>Export PDF
            </a>
            <a href="{{ route('guru.reports.excel', request()->query()) }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-semibold">
                <i class="fas fa-file-excel mr-2"></i>Export Excel
            </a>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b">
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">No</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Tanggal</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Mata Pelajaran</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Kelas</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Pertemuan</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Jam</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">TP</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $index => $log)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4">{{ $logs->firstItem() + $index }}</td>
                            <td class="py-3 px-4 whitespace-nowrap text-sm">
                                {{ $log->log_date->format('d M Y') }}
                            </td>
                            <td class="py-3 px-4 font-medium">{{ $log->subject }}</td>
                            <td class="py-3 px-4">
                                <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-sm">
                                    {{ $log->class }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-sm font-semibold">
                                    #{{ $log->meeting_number }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-sm">{{ $log->time_slot }}</td>
                            <td class="py-3 px-4 text-sm text-gray-600">
                                {{ Str::limit($log->tp, 40) }}
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-600">
                                {{ $log->notes ? Str::limit($log->notes, 30) : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 text-gray-500">
                                <i class="fas fa-inbox text-5xl mb-4 text-gray-300"></i>
                                <p class="text-lg">Tidak ada data untuk periode ini</p>
                                <a href="{{ route('guru.jurnal.create') }}" class="text-blue-600 hover:underline mt-2 inline-block">
                                    Tambah jurnal sekarang
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $logs->appends(request()->query())->links() }}
        </div>

        <!-- Summary -->
        @if($logs->total() > 0)
        <div class="mt-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
            <p class="text-sm text-gray-700">
                <strong>Total:</strong> {{ $logs->total() }} jurnal pada periode ini
            </p>
        </div>
        @endif
    </div>
</x-layout>