<x-layout>
    <x-slot name="title">Detail Barang</x-slot>
    <x-slot name="header">Detail Barang</x-slot>
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Detail Barang</h2>
        <div class="space-x-2">
            <a href="{{ route('admin.items.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <div class="mb-4">
                <label class="block text-gray-600 text-sm font-semibold mb-1">Nama Barang</label>
                <p class="text-gray-900">{{ $item->name }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-600 text-sm font-semibold mb-1">Kode Barang</label>
                <p class="text-gray-900">{{ $item->code }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-600 text-sm font-semibold mb-1">Kategori</label>
                <p class="text-gray-900">{{ $item->category ?? '-' }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-600 text-sm font-semibold mb-1">Lokasi</label>
                <p class="text-gray-900">{{ $item->location ?? '-' }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-600 text-sm font-semibold mb-1">Kondisi</label>
                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full 
                    @if($item->condition === 'baik') bg-green-100 text-green-800
                    @elseif($item->condition === 'rusak ringan') bg-yellow-100 text-yellow-800
                    @else bg-red-100 text-red-800
                    @endif">
                    {{ ucfirst($item->condition) }}
                </span>
            </div>

            <div class="mb-4">
                <label class="block text-gray-600 text-sm font-semibold mb-1">Jumlah</label>
                <p class="text-gray-900">{{ $item->quantity }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-600 text-sm font-semibold mb-1">Harga</label>
                <p class="text-gray-900">{{ $item->price ? 'Rp ' . number_format($item->price, 0, ',', '.') : '-' }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-600 text-sm font-semibold mb-1">Tanggal Pembelian</label>
                <p class="text-gray-900">{{ $item->purchase_date ? $item->purchase_date->format('d M Y') : '-' }}</p>
            </div>
        </div>

        <div>
            <div class="mb-4">
                <label class="block text-gray-600 text-sm font-semibold mb-2">QR Code</label>
                <div class="bg-white p-4 border rounded-lg inline-block">
                    <img src="{{ asset($item->barcode_path) }}" alt="QR Code" class="h-48">
                    <p class="text-center text-sm text-gray-600 mt-2">{{ $item->code }}</p>
                </div>
                <div class="mt-3 space-x-2">
                    <a href="{{ route('admin.items.print', $item) }}" target="_blank" class="inline-flex items-center bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg transition">
                        <i class="fas fa-print mr-2"></i>Cetak QR Code
                    </a>
                    <a href="{{ route('admin.items.barcode', $item) }}" class="inline-flex items-center bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition">
                        <i class="fas fa-download mr-2"></i>Download QR Code
                    </a>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-600 text-sm font-semibold mb-1">Deskripsi</label>
                <p class="text-gray-900">{{ $item->description ?? '-' }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-600 text-sm font-semibold mb-1">Total Scan</label>
                <p class="text-gray-900">{{ $item->total_scans }} kali</p>
            </div>
        </div>
    </div>

    <!-- Riwayat Scan -->
    <div class="mt-8">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Riwayat Scan (10 Terakhir)</h3>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal & Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Staff</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe Scan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($item->scans->take(10) as $scan)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $scan->scanned_at->format('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $scan->user->name }}
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
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada riwayat scan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</x-layout>
