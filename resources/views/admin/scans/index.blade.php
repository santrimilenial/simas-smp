<x-layout>
    <x-slot name="title">Riwayat Scan</x-slot>
    <x-slot name="header">Riwayat Scan QR Code</x-slot>
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Riwayat Scan QR Code</h2>
    </div>

    <!-- Filter -->
    <form method="GET" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2">Tanggal Mulai</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        
        <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2">Tanggal Akhir</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="flex items-end">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition w-full">
                <i class="fas fa-filter mr-2"></i>Filter
            </button>
        </div>

        <div class="flex items-end">
            <a href="{{ route('admin.scans.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition w-full text-center">
                <i class="fas fa-redo mr-2"></i>Reset
            </a>
        </div>
    </form>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal & Waktu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barang</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Staff</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe Scan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($scans as $scan)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $scan->scanned_at->format('d M Y H:i') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <a href="{{ route('admin.items.show', $scan->item) }}" class="text-blue-600 hover:text-blue-900">
                                {{ $scan->item->name }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $scan->item->code }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <a href="{{ route('admin.staff.show', $scan->user) }}" class="text-blue-600 hover:text-blue-900">
                                {{ $scan->user->name }}
                            </a>
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
                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada riwayat scan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $scans->links() }}
    </div>
</div>
</x-layout>
