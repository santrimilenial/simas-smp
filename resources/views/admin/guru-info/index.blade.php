<x-layout>
    <x-slot name="title">Informasi Guru</x-slot>
    <x-slot name="header">Informasi Guru</x-slot>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div>
                <h2 class="text-lg font-bold text-gray-800"><i class="fas fa-id-card mr-2 text-blue-600"></i>Data Informasi Guru</h2>
                <p class="text-sm text-gray-500 mt-1">Menampilkan informasi lengkap semua guru termasuk masa kerja</p>
            </div>
            <form method="GET" action="{{ route('admin.guru-info.index') }}" class="w-full md:w-auto">
                <div class="relative">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, NIY, jabatan..."
                        class="w-full md:w-72 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            @php
                $allGurus = \App\Models\User::guru()->get();
                $totalGuru = $allGurus->count();
                $avgYears = $allGurus->whereNotNull('join_year')->avg(function($g) { return now()->year - $g->join_year; });
                $seniorCount = $allGurus->filter(fn($g) => $g->join_year && (now()->year - $g->join_year) >= 5)->count();
                $newCount = $allGurus->filter(fn($g) => $g->join_year && (now()->year - $g->join_year) < 2)->count();
            @endphp
            <div class="bg-blue-50 rounded-lg p-4 text-center">
                <p class="text-xs text-blue-600 font-semibold uppercase">Total Guru</p>
                <p class="text-2xl font-bold text-blue-800 mt-1">{{ $totalGuru }}</p>
            </div>
            <div class="bg-purple-50 rounded-lg p-4 text-center">
                <p class="text-xs text-purple-600 font-semibold uppercase">Rata-rata Masa Kerja</p>
                <p class="text-2xl font-bold text-purple-800 mt-1">{{ $avgYears ? round($avgYears, 1) : 0 }} <span class="text-sm font-normal">tahun</span></p>
            </div>
            <div class="bg-green-50 rounded-lg p-4 text-center">
                <p class="text-xs text-green-600 font-semibold uppercase">Senior (≥5 Tahun)</p>
                <p class="text-2xl font-bold text-green-800 mt-1">{{ $seniorCount }}</p>
            </div>
            <div class="bg-orange-50 rounded-lg p-4 text-center">
                <p class="text-xs text-orange-600 font-semibold uppercase">Baru (&lt;2 Tahun)</p>
                <p class="text-2xl font-bold text-orange-800 mt-1">{{ $newCount }}</p>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b">
                        <th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm">No</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm">Nama</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm">NIY</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm">Jabatan</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm">Alamat</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm">Tahun Masuk</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm">Masa Kerja</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gurus as $index => $guru)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4 text-sm">{{ $gurus->firstItem() + $index }}</td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $guru->avatar_url }}" alt="{{ $guru->name }}" class="w-8 h-8 rounded-full object-cover">
                                    <div>
                                        <p class="font-semibold text-gray-800 text-sm">{{ $guru->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $guru->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-600">{{ $guru->niy }}</td>
                            <td class="py-3 px-4 text-sm text-gray-600">{{ $guru->position ?? '-' }}</td>
                            <td class="py-3 px-4 text-sm text-gray-600">{{ $guru->address ? Str::limit($guru->address, 40) : '-' }}</td>
                            <td class="py-3 px-4 text-sm text-gray-600">{{ $guru->join_year ?? '-' }}</td>
                            <td class="py-3 px-4">
                                @if($guru->join_year)
                                    @php $years = now()->year - $guru->join_year; @endphp
                                    <span @class([
                                        'px-2.5 py-1 rounded-full text-xs font-semibold',
                                        'bg-green-100 text-green-700' => $years >= 5,
                                        'bg-blue-100 text-blue-700' => $years >= 2 && $years < 5,
                                        'bg-orange-100 text-orange-700' => $years < 2,
                                    ])>
                                        {{ $years }} tahun
                                    </span>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-2"></i>
                                <p>Belum ada data guru</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $gurus->withQueryString()->links() }}
        </div>
    </div>
</x-layout>
