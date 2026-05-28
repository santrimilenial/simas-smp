<x-layout>
    <x-slot name="title">Laporan Barang</x-slot>
    <x-slot name="header">Laporan Barang Inventaris</x-slot>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase">Total Barang</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($totalItems) }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ number_format($totalQuantity) }} unit</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-boxes text-blue-500 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase">Kondisi Baik</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ number_format($conditionStats['baik']) }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $totalItems > 0 ? round($conditionStats['baik'] / $totalItems * 100) : 0 }}% dari total</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase">Rusak Ringan</p>
                    <p class="text-2xl font-bold text-yellow-600 mt-1">{{ number_format($conditionStats['rusak_ringan']) }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $totalItems > 0 ? round($conditionStats['rusak_ringan'] / $totalItems * 100) : 0 }}% dari total</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase">Rusak Berat</p>
                    <p class="text-2xl font-bold text-red-600 mt-1">{{ number_format($conditionStats['rusak_berat']) }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $totalItems > 0 ? round($conditionStats['rusak_berat'] / $totalItems * 100) : 0 }}% dari total</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-times-circle text-red-500 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Additional Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-md p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase">Total Nilai Aset</p>
                    <p class="text-xl font-bold text-gray-800 mt-1">Rp {{ number_format($totalValue, 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-purple-500 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase">Scan 30 Hari Terakhir</p>
                    <p class="text-xl font-bold text-gray-800 mt-1">{{ number_format($recentScans) }} scan</p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-qrcode text-indigo-500 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Condition Bar Chart --}}
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-chart-bar text-blue-500 mr-2"></i>Distribusi Kondisi Barang
        </h3>
        <div class="space-y-4">
            @php
                $maxCount = max($conditionStats['baik'], $conditionStats['rusak_ringan'], $conditionStats['rusak_berat'], 1);
            @endphp
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="font-semibold text-gray-700">Baik</span>
                    <span class="text-gray-500">{{ $conditionStats['baik'] }} barang</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-4">
                    <div class="bg-green-500 h-4 rounded-full transition-all" style="width: {{ ($conditionStats['baik'] / $maxCount) * 100 }}%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="font-semibold text-gray-700">Rusak Ringan</span>
                    <span class="text-gray-500">{{ $conditionStats['rusak_ringan'] }} barang</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-4">
                    <div class="bg-yellow-500 h-4 rounded-full transition-all" style="width: {{ ($conditionStats['rusak_ringan'] / $maxCount) * 100 }}%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="font-semibold text-gray-700">Rusak Berat</span>
                    <span class="text-gray-500">{{ $conditionStats['rusak_berat'] }} barang</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-4">
                    <div class="bg-red-500 h-4 rounded-full transition-all" style="width: {{ ($conditionStats['rusak_berat'] / $maxCount) * 100 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter & Export --}}
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
            <h3 class="text-lg font-bold text-gray-800">
                <i class="fas fa-list text-blue-500 mr-2"></i>Daftar Barang
            </h3>
            <div class="flex gap-2">
                <a href="{{ route('admin.items.report.pdf', request()->query()) }}" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                    <i class="fas fa-file-pdf mr-1"></i>Export PDF
                </a>
                <a href="{{ route('admin.items.report.excel', request()->query()) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                    <i class="fas fa-file-excel mr-1"></i>Export Excel
                </a>
            </div>
        </div>

        <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 mb-6">
            <div>
                <label class="block text-gray-600 text-xs font-semibold mb-1">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama / Kode barang..." class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-600 text-xs font-semibold mb-1">Kondisi</label>
                <select name="condition" class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Kondisi</option>
                    <option value="baik" {{ request('condition') == 'baik' ? 'selected' : '' }}>Baik</option>
                    <option value="rusak ringan" {{ request('condition') == 'rusak ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="rusak berat" {{ request('condition') == 'rusak berat' ? 'selected' : '' }}>Rusak Berat</option>
                </select>
            </div>
            <div>
                <label class="block text-gray-600 text-xs font-semibold mb-1">Kategori</label>
                <select name="category" class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-gray-600 text-xs font-semibold mb-1">Lokasi</label>
                <select name="location" class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Lokasi</option>
                    @foreach($locations as $loc)
                        <option value="{{ $loc }}" {{ request('location') == $loc ? 'selected' : '' }}>{{ $loc }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition flex-1">
                    <i class="fas fa-filter mr-1"></i>Filter
                </button>
                <a href="{{ route('admin.items.report') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-3 py-2 rounded-lg text-sm transition" title="Reset">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Barang</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lokasi</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kondisi</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Nilai</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($items as $index => $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 text-sm font-mono font-medium text-gray-900">{{ $item->code }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">
                                <a href="{{ route('admin.items.show', $item) }}" class="text-blue-600 hover:text-blue-800 hover:underline">
                                    {{ $item->name }}
                                </a>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $item->category ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $item->location ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span @class([
                                    'px-2 py-0.5 text-xs font-semibold rounded-full',
                                    'bg-green-100 text-green-800' => $item->condition === 'baik',
                                    'bg-yellow-100 text-yellow-800' => $item->condition === 'rusak ringan',
                                    'bg-red-100 text-red-800' => $item->condition === 'rusak berat',
                                ])>
                                    {{ ucfirst($item->condition) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $item->quantity }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $item->price ? 'Rp ' . number_format($item->price, 0, ',', '.') : '-' }}</td>
                            <td class="px-4 py-3 text-sm font-semibold text-gray-700">{{ $item->price ? 'Rp ' . number_format($item->price * $item->quantity, 0, ',', '.') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-6 text-center text-sm text-gray-500">
                                <i class="fas fa-inbox text-gray-300 text-3xl mb-2"></i>
                                <p>Tidak ada data barang ditemukan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($items->count() > 0)
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="6" class="px-4 py-3 text-sm font-bold text-gray-700 text-right">Total:</td>
                        <td class="px-4 py-3 text-sm font-bold text-gray-700">{{ number_format($items->sum('quantity')) }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">-</td>
                        <td class="px-4 py-3 text-sm font-bold text-gray-700">Rp {{ number_format($items->sum(fn($i) => ($i->price ?? 0) * $i->quantity), 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>

        <div class="mt-4 text-sm text-gray-500">
            Menampilkan {{ $items->count() }} barang
        </div>
    </div>
</x-layout>
