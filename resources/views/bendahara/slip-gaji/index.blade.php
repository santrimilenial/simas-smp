<x-layout>
    <x-slot name="title">Slip Gaji</x-slot>
    <x-slot name="header">Kelola Slip Gaji</x-slot>

    <div class="p-4 md:p-8">
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <!-- Filter Section -->
            <form method="GET" action="{{ route('bendahara.slip-gaji.index') }}" class="mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Bulan</label>
                        <select name="month" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ request('month', now()->month) == $i ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($i)->isoFormat('MMMM') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun</label>
                        <select name="year" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            @for($i = now()->year; $i >= now()->year - 5; $i--)
                                <option value="{{ $i }}" {{ request('year', now()->year) == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Semua Status</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Sudah Dibayar</option>
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                            <i class="fas fa-search mr-2"></i>Filter
                        </button>
                        <a href="{{ route('bendahara.slip-gaji.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg font-semibold transition">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </div>
            </form>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-lg border-l-4 border-blue-500">
                    <p class="text-sm text-gray-600">Total Slip</p>
                    <h3 class="text-2xl font-bold text-blue-700">{{ $stats['total'] }}</h3>
                </div>
                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-4 rounded-lg border-l-4 border-yellow-500">
                    <p class="text-sm text-gray-600">Draft</p>
                    <h3 class="text-2xl font-bold text-yellow-700">{{ $stats['draft'] }}</h3>
                </div>
                <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 p-4 rounded-lg border-l-4 border-indigo-500">
                    <p class="text-sm text-gray-600">Disetujui</p>
                    <h3 class="text-2xl font-bold text-indigo-700">{{ $stats['approved'] }}</h3>
                </div>
                <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-lg border-l-4 border-green-500">
                    <p class="text-sm text-gray-600">Sudah Dibayar</p>
                    <h3 class="text-2xl font-bold text-green-700">{{ $stats['paid'] }}</h3>
                </div>
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-lg border-l-4 border-purple-500">
                    <p class="text-sm text-gray-600">Total Nominal</p>
                    <h3 class="text-lg font-bold text-purple-700">Rp {{ number_format($stats['total_amount'], 0, ',', '.') }}</h3>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-800">
                    Slip Gaji - {{ \Carbon\Carbon::create()->month((int)request('month', now()->month))->isoFormat('MMMM') }} {{ request('year', now()->year) }}
                </h3>
                <a href="{{ route('bendahara.slip-gaji.create', ['month' => $month, 'year' => $year]) }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                    <i class="fas fa-plus mr-2"></i>Buat Slip Gaji
                </a>
            </div>

            <!-- Slip Gaji Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama Guru</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Jam Mengajar</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Rate/Jam</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Total Gaji</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($slipGaji as $index => $slip)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $slipGaji->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-green-100 rounded-full flex items-center justify-center">
                                            <span class="text-green-600 font-semibold">{{ substr($slip->user->name, 0, 1) }}</span>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-semibold text-gray-900">{{ $slip->user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $slip->user->niy }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center justify-center bg-purple-100 text-purple-800 px-3 py-1 rounded-lg font-bold">
                                        {{ $slip->total_teaching_hours }} jam
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-600">
                                    {{ $slip->formatted_rate }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center justify-center bg-green-100 text-green-800 px-3 py-1 rounded-lg font-bold">
                                        {{ $slip->formatted_total }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                        @if($slip->status === 'draft') bg-yellow-100 text-yellow-800
                                        @elseif($slip->status === 'approved') bg-blue-100 text-blue-800
                                        @else bg-green-100 text-green-800
                                        @endif">
                                        {{ $slip->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('bendahara.slip-gaji.show', $slip) }}" 
                                           class="text-blue-600 hover:text-blue-800" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('bendahara.slip-gaji.print', $slip) }}" 
                                           class="text-green-600 hover:text-green-800" title="Print PDF">
                                            <i class="fas fa-print"></i>
                                        </a>
                                        @if($slip->status === 'draft')
                                            <form action="{{ route('bendahara.slip-gaji.update-status', $slip) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="text-indigo-600 hover:text-indigo-800" title="Setujui">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('bendahara.slip-gaji.destroy', $slip) }}" method="POST" class="inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="text-red-600 hover:text-red-800 btn-delete" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @elseif($slip->status === 'approved')
                                            <form action="{{ route('bendahara.slip-gaji.update-status', $slip) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="paid">
                                                <button type="submit" class="text-green-600 hover:text-green-800" title="Tandai Sudah Dibayar">
                                                    <i class="fas fa-money-bill-wave"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    <i class="fas fa-file-invoice-dollar text-4xl mb-3"></i>
                                    <p>Belum ada slip gaji untuk periode ini</p>
                                    <a href="{{ route('bendahara.slip-gaji.create', ['month' => $month, 'year' => $year]) }}" 
                                       class="inline-block mt-4 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                                        <i class="fas fa-plus mr-2"></i>Buat Slip Gaji Sekarang
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($slipGaji->hasPages())
                <div class="mt-4">
                    {{ $slipGaji->links() }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                
                Swal.fire({
                    title: 'Hapus Slip Gaji?',
                    text: 'Yakin ingin menghapus slip gaji ini? Data tidak dapat dikembalikan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
    @endpush
</x-layout>
