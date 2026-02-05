<x-layout>
    <x-slot name="title">Detail Guru - {{ $guru->name }}</x-slot>
    <x-slot name="header">Detail Data Guru</x-slot>

    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.guru.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-semibold">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Guru
        </a>
    </div>

    <!-- Guru Profile Card -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex items-start justify-between mb-6">
            <div class="flex items-center">
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-3xl font-bold mr-4">
                    {{ strtoupper(substr($guru->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $guru->name }}</h2>
                    <p class="text-gray-600 mt-1">
                        <i class="fas fa-id-card mr-2"></i>{{ $guru->niy }}
                    </p>
                </div>
            </div>
            <div class="flex gap-2">
                <button onclick="openEditGuruModal({{ $guru->id }})" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold">
                    <i class="fas fa-edit mr-2"></i>Edit
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Contact Information -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Informasi Kontak</h3>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                            <i class="fas fa-envelope text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="font-medium text-gray-800">{{ $guru->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mr-3">
                            <i class="fas fa-phone text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">No. Telepon</p>
                            <p class="font-medium text-gray-800">{{ $guru->phone ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Statistik</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-4 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 text-sm">Total Jurnal</p>
                                <p class="text-3xl font-bold mt-1">{{ $guru->teaching_logs_count }}</p>
                            </div>
                            <div class="bg-white/20 rounded-full p-3">
                                <i class="fas fa-book text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-4 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-100 text-sm">Status</p>
                                <p class="text-lg font-bold mt-1">Aktif</p>
                            </div>
                            <div class="bg-white/20 rounded-full p-3">
                                <i class="fas fa-check-circle text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Teaching Logs -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-800">
                <i class="fas fa-history mr-2 text-blue-600"></i>Riwayat Jurnal Mengajar
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs md:text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b">
                        <th class="text-left py-2 md:py-3 px-2 md:px-4 font-semibold text-gray-700">No</th>
                        <th class="text-left py-2 md:py-3 px-2 md:px-4 font-semibold text-gray-700">Tanggal</th>
                        <th class="text-left py-2 md:py-3 px-2 md:px-4 font-semibold text-gray-700">Kelas</th>
                        <th class="text-left py-2 md:py-3 px-2 md:px-4 font-semibold text-gray-700">Mapel</th>
                        <th class="text-left py-2 md:py-3 px-2 md:px-4 font-semibold text-gray-700">Jam</th>
                        <th class="text-left py-2 md:py-3 px-2 md:px-4 font-semibold text-gray-700 hidden md:table-cell">Materi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $index => $log)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-2 md:py-3 px-2 md:px-4">{{ $logs->firstItem() + $index }}</td>
                            <td class="py-2 md:py-3 px-2 md:px-4 whitespace-nowrap">{{ $log->log_date->format('d M Y') }}</td>
                            <td class="py-2 md:py-3 px-2 md:px-4">
                                <span class="bg-purple-100 text-purple-700 px-1.5 md:px-2 py-0.5 md:py-1 rounded text-xs font-semibold">
                                    {{ $log->class }}
                                </span>
                            </td>
                            <td class="py-2 md:py-3 px-2 md:px-4 font-medium">{{ $log->subject }}</td>
                            <td class="py-2 md:py-3 px-2 md:px-4">
                                <span class="bg-blue-100 text-blue-700 px-1.5 md:px-2 py-0.5 md:py-1 rounded text-xs">
                                    {{ $log->time_slot }}
                                </span>
                            </td>
                            <td class="py-2 md:py-3 px-2 md:px-4 hidden md:table-cell">
                                <p class="text-gray-700 line-clamp-2">{{ $log->material }}</p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-2"></i>
                                <p>Belum ada jurnal mengajar</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($logs->hasPages())
            <div class="mt-6">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

    <!-- Edit Modal -->
    <x-modal id="modalEditGuru" title="Edit Data Guru">
        <form method="POST" id="editGuruForm" action="">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="editGuruId">
            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="editGuruName" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">NIY <span class="text-red-500">*</span></label>
                    <input type="text" name="niy" id="editGuruNiy" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="editGuruEmail" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">No. Telepon</label>
                    <input type="text" name="phone" id="editGuruPhone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Password Baru</label>
                    <input type="password" name="password" id="editGuruPassword" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah (minimal 8 karakter)</p>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="editGuruPasswordConfirmation" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal('modalEditGuru')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-semibold">
                    Batal
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold">
                    <i class="fas fa-save mr-2"></i>Update
                </button>
            </div>
        </form>
    </x-modal>

    @push('scripts')
    <script>
        function openEditGuruModal(guruId) {
            fetch(`/admin/guru/${guruId}/edit`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('editGuruId').value = data.id;
                    document.getElementById('editGuruName').value = data.name;
                    document.getElementById('editGuruEmail').value = data.email;
                    document.getElementById('editGuruNiy').value = data.niy;
                    document.getElementById('editGuruPhone').value = data.phone || '';
                    
                    // Update form action
                    const form = document.getElementById('editGuruForm');
                    form.action = `/admin/guru/${data.id}`;
                    
                    openModal('modalEditGuru');
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Gagal memuat data guru!',
                    });
                });
        }

        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>
    @endpush
</x-layout>
