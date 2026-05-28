<x-layout>
    <x-slot name="title">Pencatatan Keuangan</x-slot>
    <x-slot name="header">Pencatatan Keuangan</x-slot>

    <style>
        input[list]::-webkit-calendar-picker-indicator {
            display: none !important;
        }
    </style>

    <div class="p-4 md:p-8">
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <!-- Filter Section -->
            <form method="GET" action="{{ route('bendahara.keuangan.index') }}" class="mb-6">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Bulan</label>
                        <select name="month" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($i)->isoFormat('MMMM') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun</label>
                        <select name="year" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            @for($i = now()->year; $i >= now()->year - 5; $i--)
                                <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis</label>
                        <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Semua Jenis</option>
                            <option value="income" {{ $type == 'income' ? 'selected' : '' }}>Pemasukan</option>
                            <option value="expense" {{ $type == 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori</label>
                        <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ $category == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                            <i class="fas fa-search mr-2"></i>Filter
                        </button>
                        <a href="{{ route('bendahara.keuangan.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg font-semibold transition">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </div>
            </form>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-lg border-l-4 border-blue-500">
                    <p class="text-sm text-gray-600">Total Transaksi</p>
                    <h3 class="text-2xl font-bold text-blue-700">{{ $stats['total_records'] }}</h3>
                </div>
                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-4 rounded-lg border-l-4 border-yellow-500">
                    <p class="text-sm text-gray-600">Saldo Awal</p>
                    <h3 class="text-lg font-bold {{ $stats['carry_over'] >= 0 ? 'text-yellow-700' : 'text-red-700' }}">
                        Rp {{ number_format($stats['carry_over'], 0, ',', '.') }}
                    </h3>
                </div>
                <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-lg border-l-4 border-green-500">
                    <p class="text-sm text-gray-600">Total Pemasukan</p>
                    <h3 class="text-lg font-bold text-green-700">Rp {{ number_format($stats['total_income'], 0, ',', '.') }}</h3>
                </div>
                <div class="bg-gradient-to-br from-red-50 to-red-100 p-4 rounded-lg border-l-4 border-red-500">
                    <p class="text-sm text-gray-600">Total Pengeluaran</p>
                    <h3 class="text-lg font-bold text-red-700">Rp {{ number_format($stats['total_expense'], 0, ',', '.') }}</h3>
                </div>
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-lg border-l-4 border-purple-500">
                    <p class="text-sm text-gray-600">Saldo Akhir</p>
                    <h3 class="text-lg font-bold {{ $stats['balance'] >= 0 ? 'text-purple-700' : 'text-red-700' }}">
                        Rp {{ number_format($stats['balance'], 0, ',', '.') }}
                    </h3>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4">
                <h3 class="text-lg font-bold text-gray-800">
                    Catatan Keuangan - {{ \Carbon\Carbon::create()->month((int)$month)->isoFormat('MMMM') }} {{ $year }}
                </h3>
                <div class="flex gap-2">
                    <a href="{{ route('bendahara.keuangan.export-pdf', ['month' => $month, 'year' => $year]) }}" 
                       class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                        <i class="fas fa-file-pdf mr-2"></i>Export PDF
                    </a>
                    <button onclick="openModal('modalCreate')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                        <i class="fas fa-plus mr-2"></i>Tambah Catatan
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Jenis</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Deskripsi</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Jumlah</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($records as $index => $record)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $records->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $record->record_date->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                        {{ $record->type === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        <i class="fas {{ $record->type === 'income' ? 'fa-arrow-down' : 'fa-arrow-up' }} mr-1"></i>
                                        {{ $record->type_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-semibold">{{ $record->category }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">
                                    {{ $record->description }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <span class="font-bold {{ $record->type === 'income' ? 'text-green-700' : 'text-red-700' }}">
                                        {{ $record->type === 'income' ? '+' : '-' }} {{ $record->formatted_amount }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button class="text-blue-600 hover:text-blue-800 btn-edit" title="Edit"
                                                data-id="{{ $record->id }}"
                                                data-date="{{ $record->record_date->format('Y-m-d') }}"
                                                data-type="{{ $record->type }}"
                                                data-category="{{ $record->category }}"
                                                data-description="{{ $record->description }}"
                                                data-amount="{{ $record->amount }}"
                                                data-notes="{{ $record->notes }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('bendahara.keuangan.destroy', $record) }}" method="POST" class="inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="text-red-600 hover:text-red-800 btn-delete" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    <i class="fas fa-receipt text-4xl mb-3"></i>
                                    <p>Belum ada catatan keuangan untuk periode ini</p>
                                    <button onclick="openModal('modalCreate')" 
                                            class="inline-block mt-4 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                                        <i class="fas fa-plus mr-2"></i>Tambah Catatan Sekarang
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($records->hasPages())
                <div class="mt-4">
                    {{ $records->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Create -->
    <div id="modalCreate" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b">
                <h3 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-plus-circle text-green-600 mr-2"></i>Tambah Catatan Keuangan
                </h3>
                <button onclick="closeModal('modalCreate')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form action="{{ route('bendahara.keuangan.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="record_date" value="{{ old('record_date', now()->format('Y-m-d')) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Jenis Transaksi <span class="text-red-500">*</span></label>
                    <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        <option value="">Pilih Jenis</option>
                        <option value="income" {{ old('type') == 'income' ? 'selected' : '' }}>Pemasukan</option>
                        <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                    <input type="text" name="category" value="{{ old('category') }}" placeholder="Contoh: SPP, Gaji, Operasional, Donasi, dll"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required list="category-list">
                    <datalist id="category-list">
                        <option value="SPP">
                        <option value="Gaji">
                        <option value="Operasional">
                        <option value="Donasi">
                        <option value="Pemeliharaan">
                        <option value="ATK">
                        <option value="Kegiatan Sekolah">
                        <option value="Lain-lain">
                    </datalist>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="2" placeholder="Jelaskan detail transaksi..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>{{ old('description') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Jumlah (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="amount" value="{{ old('amount') }}" placeholder="0" min="1"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Catatan</label>
                    <textarea name="notes" rows="2" placeholder="Catatan tambahan (opsional)"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('notes') }}</textarea>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2.5 rounded-lg font-semibold transition">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                    <button type="button" onclick="closeModal('modalCreate')" class="px-6 bg-gray-300 hover:bg-gray-400 text-gray-800 py-2.5 rounded-lg font-semibold transition">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div id="modalEdit" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b">
                <h3 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-edit text-blue-600 mr-2"></i>Edit Catatan Keuangan
                </h3>
                <button onclick="closeModal('modalEdit')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form id="editForm" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="record_date" id="edit_record_date"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Jenis Transaksi <span class="text-red-500">*</span></label>
                    <select name="type" id="edit_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        <option value="income">Pemasukan</option>
                        <option value="expense">Pengeluaran</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                    <input type="text" name="category" id="edit_category" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required list="category-list-edit">
                    <datalist id="category-list-edit">
                        <option value="SPP">
                        <option value="Gaji">
                        <option value="Operasional">
                        <option value="Donasi">
                        <option value="Pemeliharaan">
                        <option value="ATK">
                        <option value="Kegiatan Sekolah">
                        <option value="Lain-lain">
                    </datalist>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi <span class="text-red-500">*</span></label>
                    <textarea name="description" id="edit_description" rows="2"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Jumlah (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="amount" id="edit_amount" min="1"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Catatan</label>
                    <textarea name="notes" id="edit_notes" rows="2" placeholder="Catatan tambahan (opsional)"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg font-semibold transition">
                        <i class="fas fa-save mr-2"></i>Update
                    </button>
                    <button type="button" onclick="closeModal('modalEdit')" class="px-6 bg-gray-300 hover:bg-gray-400 text-gray-800 py-2.5 rounded-lg font-semibold transition">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Edit button listeners (safe from XSS - uses data attributes)
        document.querySelectorAll('.btn-edit').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                document.getElementById('editForm').action = '{{ url('bendahara/keuangan') }}/' + id;
                document.getElementById('edit_record_date').value = this.dataset.date;
                document.getElementById('edit_type').value = this.dataset.type;
                document.getElementById('edit_category').value = this.dataset.category;
                document.getElementById('edit_description').value = this.dataset.description;
                document.getElementById('edit_amount').value = this.dataset.amount;
                document.getElementById('edit_notes').value = this.dataset.notes || '';
                openModal('modalEdit');
            });
        });

        // Delete confirmation
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                
                Swal.fire({
                    title: 'Hapus Catatan?',
                    text: 'Yakin ingin menghapus catatan keuangan ini? Data tidak dapat dikembalikan.',
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

        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal('modalCreate');
                closeModal('modalEdit');
            }
        });

        // Auto-open create modal if there are validation errors
        @if($errors->any())
            openModal('modalCreate');
        @endif
    </script>
    @endpush
</x-layout>
