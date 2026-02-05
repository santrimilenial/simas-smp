<x-layout>
    <x-slot name="title">Detail Staff</x-slot>
    <x-slot name="header">Detail Staff</x-slot>
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Detail Staff</h2>
        <div class="space-x-2">
            <a href="{{ route('admin.staff.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <div class="mb-4">
                <label class="block text-gray-600 text-sm font-semibold mb-1">Nama Lengkap</label>
                <p class="text-gray-900">{{ $staff->name }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-600 text-sm font-semibold mb-1">Email</label>
                <p class="text-gray-900">{{ $staff->email }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-600 text-sm font-semibold mb-1">No. HP</label>
                <p class="text-gray-900">{{ $staff->phone ?? '-' }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-600 text-sm font-semibold mb-1">Terdaftar Sejak</label>
                <p class="text-gray-900">{{ $staff->created_at->format('d M Y H:i') }}</p>
            </div>
        </div>

        <div>
            <div class="mb-4">
                <label class="block text-gray-600 text-sm font-semibold mb-1">Total Scan</label>
                <p class="text-gray-900 text-2xl font-bold">{{ $staff->scans->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Riwayat Scan Terakhir -->
    <div class="mt-8">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Riwayat Scan Terakhir (10)</h3>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal & Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe Scan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($staff->scans as $scan)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $scan->scanned_at->format('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $scan->item->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $scan->item->code }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $scan->scan_type === 'camera' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                    {{ ucfirst($scan->scan_type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $scan->location ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada riwayat scan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</x-layout>
