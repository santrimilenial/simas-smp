<x-layout>
    <x-slot name="title">Riwayat Scan - Inventory System</x-slot>
    <x-slot name="header">Riwayat Scan Saya</x-slot>

    <div class="max-w-7xl mx-auto">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Total Scan</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $scans->total() }}</p>
                    </div>
                    <div class="bg-blue-100 p-4 rounded-full">
                        <i class="fas fa-qrcode text-blue-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Scan Hari Ini</p>
                        <p class="text-3xl font-bold text-green-600">{{ $scans->where('scanned_at', '>=', today())->count() }}</p>
                    </div>
                    <div class="bg-green-100 p-4 rounded-full">
                        <i class="fas fa-calendar-day text-green-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Scan Minggu Ini</p>
                        <p class="text-3xl font-bold text-purple-600">{{ $scans->where('scanned_at', '>=', now()->startOfWeek())->count() }}</p>
                    </div>
                    <div class="bg-purple-100 p-4 rounded-full">
                        <i class="fas fa-calendar-week text-purple-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- History Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">Riwayat Scan QR Code</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal & Waktu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kondisi Laporan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe Scan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($scans as $scan)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $scan->scanned_at->format('d M Y H:i') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $scan->item->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $scan->item->code }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($scan->condition_report)
                                        @php
                                            $condClass = match($scan->condition_report) {
                                                'baik' => 'bg-green-100 text-green-800',
                                                'rusak ringan' => 'bg-yellow-100 text-yellow-800',
                                                'rusak berat' => 'bg-red-100 text-red-800',
                                                default => 'bg-gray-100 text-gray-800',
                                            };
                                        @endphp
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $condClass }}">
                                            {{ ucwords($scan->condition_report) }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $scan->scan_type === 'camera' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                        {{ ucfirst($scan->scan_type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $scan->location ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $scan->notes ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500">
                                    <i class="fas fa-inbox text-4xl text-gray-300 mb-2 block"></i>
                                    <p>Belum ada riwayat scan</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200">
                {{ $scans->links() }}
            </div>
        </div>
    </div>
</x-layout>
