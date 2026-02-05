<x-layout>
    <x-slot name="title">Tahun Ajaran</x-slot>
    <x-slot name="header">Kelola Tahun Ajaran</x-slot>

    <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
        <!-- Header Actions -->
        <div class="flex flex-col gap-3 md:gap-4 mb-4 md:mb-6">
            <!-- Search -->
            <form method="GET" action="{{ route('admin.academic-years.index') }}">
                <div class="flex flex-col sm:flex-row gap-2 md:gap-3">
                    <div class="relative flex-1">
                        <input 
                            type="text" 
                            name="search"
                            value="{{ $search }}"
                            placeholder="Cari tahun ajaran..."
                            class="w-full pl-10 pr-4 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                        <i class="fas fa-search absolute left-3 top-2.5 md:top-3 text-gray-400 text-sm"></i>
                    </div>
                    
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 sm:flex-none bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-xs md:text-sm font-semibold">
                            <i class="fas fa-search mr-1"></i>Cari
                        </button>
                        <a href="{{ route('admin.academic-years.index') }}" class="flex-1 sm:flex-none bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg text-xs md:text-sm font-semibold text-center">
                            <i class="fas fa-redo mr-1"></i>Reset
                        </a>
                    </div>
                </div>
            </form>

            <div>
                <button onclick="openModal('modalCreateAcademicYear')" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 md:px-6 py-2.5 md:py-2 rounded-lg text-sm md:text-base font-semibold">
                    <i class="fas fa-plus mr-2"></i>Tambah Tahun Ajaran
                </button>
            </div>
        </div>

        <!-- Info Box -->
        <div class="bg-blue-50 border-l-4 border-blue-500 p-3 md:p-4 mb-4 md:mb-6">
            <div class="flex items-start justify-between">
                <div class="flex items-start flex-1">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-500 text-sm md:text-base"></i>
                    </div>
                    <div class="ml-2 md:ml-3">
                        <p class="text-xs md:text-sm text-blue-700 leading-relaxed">
                            <strong>Tips:</strong> Tahun ajaran yang aktif akan menjadi default saat guru mengisi jurnal mengajar. Hanya satu tahun ajaran yang bisa aktif dalam satu waktu.
                        </p>
                    </div>
                </div>
                <div class="ml-4">
                    <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-bold">
                        {{ $academicYears->total() }} Data
                    </span>
                </div>
            </div>
        </div>

        <!-- Table Desktop -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b">
                        <th class="text-left py-3 px-4 font-semibold text-gray-700 w-12">No</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Tahun Ajaran</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Periode</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-700 w-24">Status</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-700 w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($academicYears as $index => $year)
                        <tr class="border-b hover:bg-gray-50" data-id="{{ $year->id }}">
                            <td class="py-3 px-4">{{ $academicYears->firstItem() + $index }}</td>
                            <td class="py-3 px-4">
                                <span class="font-semibold text-gray-800">{{ $year->full_name }}</span>
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-600">
                                {{ $year->start_date->format('d M Y') }} - {{ $year->end_date->format('d M Y') }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($year->is_active)
                                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-semibold">
                                        <i class="fas fa-check-circle mr-1"></i>Aktif
                                    </span>
                                @else
                                    <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-semibold">
                                        Non-aktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if(!$year->is_active)
                                    <form action="{{ route('admin.academic-years.set-active', $year->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="text-green-600 hover:text-green-800 mr-2" title="Aktifkan">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                                
                                <button onclick="openEditModal({{ $year->id }})" class="text-blue-600 hover:text-blue-800 mr-2" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                
                                <form action="{{ route('admin.academic-years.destroy', $year->id) }}"
                                    method="POST"
                                    class="inline delete-form"
                                    data-id="{{ $year->id }}"
                                    data-name="{{ $year->full_name }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-12 text-gray-500">
                                <i class="fas fa-calendar-alt text-5xl mb-4 text-gray-300"></i>
                                <p class="text-lg mb-2">Belum ada Tahun Ajaran</p>
                                <button onclick="openModal('modalCreateAcademicYear')" class="text-blue-600 hover:underline">
                                    <i class="fas fa-plus-circle mr-1"></i>Tambah tahun ajaran pertama
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden space-y-3">
            @forelse($academicYears as $index => $year)
                <div class="bg-white border rounded-xl p-4 shadow-sm" data-id="{{ $year->id }}">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs text-gray-500">#{{ $academicYears->firstItem() + $index }}</span>
                                @if($year->is_active)
                                    <span class="inline-flex items-center bg-green-100 text-green-700 px-2 py-0.5 rounded-full text-xs font-semibold">
                                        <i class="fas fa-check-circle mr-1 text-[10px]"></i>Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full text-xs font-semibold">
                                        Non-aktif
                                    </span>
                                @endif
                            </div>
                            <h3 class="font-bold text-gray-800 text-lg">{{ $year->full_name }}</h3>
                        </div>
                    </div>
                    
                    <div class="flex items-center text-sm text-gray-600 mb-4">
                        <i class="fas fa-calendar-alt text-gray-400 mr-2"></i>
                        <span>{{ $year->start_date->format('d M Y') }} - {{ $year->end_date->format('d M Y') }}</span>
                    </div>
                    
                    <div class="flex items-center justify-end gap-2 pt-3 border-t">
                        @if(!$year->is_active)
                            <form action="{{ route('admin.academic-years.set-active', $year->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="inline-flex items-center bg-green-50 text-green-600 hover:bg-green-100 px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                    <i class="fas fa-check mr-1"></i>Aktifkan
                                </button>
                            </form>
                        @endif
                        
                        <button onclick="openEditModal({{ $year->id }})" class="inline-flex items-center bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </button>
                        
                        <form action="{{ route('admin.academic-years.destroy', $year->id) }}"
                            method="POST"
                            class="inline delete-form"
                            data-id="{{ $year->id }}"
                            data-name="{{ $year->full_name }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                <i class="fas fa-trash mr-1"></i>Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 text-gray-500">
                    <i class="fas fa-calendar-alt text-5xl mb-4 text-gray-300"></i>
                    <p class="text-lg mb-2">Belum ada Tahun Ajaran</p>
                    <button onclick="openModal('modalCreateAcademicYear')" class="text-blue-600 hover:underline">
                        <i class="fas fa-plus-circle mr-1"></i>Tambah tahun ajaran pertama
                    </button>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $academicYears->appends(request()->query())->links() }}
        </div>
    </div>

    <!-- Modal Create -->
    <x-modal id="modalCreateAcademicYear" title="Tambah Tahun Ajaran" size="max-w-lg">
        <form action="{{ route('admin.academic-years.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Tahun Ajaran <span class="text-red-500">*</span></label>
                        <input 
                            type="text" 
                            name="name" 
                            value="{{ old('name') }}" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                            placeholder="Contoh: 2025/2026"
                            required
                        >
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Semester <span class="text-red-500">*</span></label>
                        <select 
                            name="semester" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                            required
                        >
                            <option value="ganjil" {{ old('semester') == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                            <option value="genap" {{ old('semester') == 'genap' ? 'selected' : '' }}>Genap</option>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Tanggal Mulai <span class="text-red-500">*</span></label>
                        <input 
                            type="date" 
                            name="start_date" 
                            value="{{ old('start_date') }}" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                            required
                        >
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Tanggal Selesai <span class="text-red-500">*</span></label>
                        <input 
                            type="date" 
                            name="end_date" 
                            value="{{ old('end_date') }}" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                            required
                        >
                    </div>
                </div>
                
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-gray-700">Jadikan Aktif</span>
                    </label>
                    <p class="text-xs text-gray-500 mt-1">Jika dicentang, tahun ajaran lain akan dinonaktifkan</p>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal('modalCreateAcademicYear')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-semibold">
                    Batal
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
            </div>
        </form>
    </x-modal>

    <!-- Modal Edit -->
    <x-modal id="modalEditAcademicYear" title="Edit Tahun Ajaran" size="max-w-lg">
        <form id="formEditAcademicYear" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Tahun Ajaran <span class="text-red-500">*</span></label>
                        <input 
                            type="text" 
                            name="name" 
                            id="edit_name"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                            required
                        >
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Semester <span class="text-red-500">*</span></label>
                        <select 
                            name="semester" 
                            id="edit_semester"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                            required
                        >
                            <option value="ganjil">Ganjil</option>
                            <option value="genap">Genap</option>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Tanggal Mulai <span class="text-red-500">*</span></label>
                        <input 
                            type="date" 
                            name="start_date" 
                            id="edit_start_date"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                            required
                        >
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Tanggal Selesai <span class="text-red-500">*</span></label>
                        <input 
                            type="date" 
                            name="end_date" 
                            id="edit_end_date"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                            required
                        >
                    </div>
                </div>
                
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" id="edit_is_active" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-gray-700">Aktif</span>
                    </label>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal('modalEditAcademicYear')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-semibold">
                    Batal
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold">
                    <i class="fas fa-save mr-2"></i>Update
                </button>
            </div>
        </form>
    </x-modal>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const academicYearData = @json($academicYears->items());
        
        function openEditModal(id) {
            const year = academicYearData.find(y => y.id === id);
            if (!year) return;
            
            document.getElementById('formEditAcademicYear').action = `/admin/academic-years/${id}`;
            document.getElementById('edit_name').value = year.name;
            document.getElementById('edit_semester').value = year.semester;
            document.getElementById('edit_start_date').value = year.start_date.split('T')[0];
            document.getElementById('edit_end_date').value = year.end_date.split('T')[0];
            document.getElementById('edit_is_active').checked = year.is_active;
            
            openModal('modalEditAcademicYear');
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Handle delete
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const yearName = this.dataset.name;

                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: `Tahun ajaran "${yearName}" akan dihapus permanen`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, hapus',
                        cancelButtonText: 'Batal'
                    }).then(result => {
                        if (!result.isConfirmed) return;

                        fetch(this.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': this.querySelector('input[name="_token"]').value,
                                'Accept': 'application/json',
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: '_method=DELETE&_token=' + this.querySelector('input[name="_token"]').value
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: data.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire('Gagal', data.message, 'error');
                            }
                        })
                        .catch((error) => {
                            console.error('Error:', error);
                            Swal.fire('Error', 'Terjadi kesalahan server', 'error');
                        });
                    });
                });
            });
        });
    </script>
</x-layout>
