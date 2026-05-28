<x-layout>
    <x-slot name="title">Mata Pelajaran</x-slot>
    <x-slot name="header">Kelola Mata Pelajaran</x-slot>

    <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
        <!-- Header Actions -->
        <div class="flex flex-col gap-3 md:gap-4 mb-4 md:mb-6">
            <!-- Search -->
            <form method="GET" action="{{ route('guru.subjects.index') }}">
                <div class="flex flex-col sm:flex-row gap-2 md:gap-3">
                    <div class="relative flex-1">
                        <input 
                            type="text" 
                            name="search"
                            value="{{ $search }}"
                            placeholder="Cari mata pelajaran..."
                            class="w-full pl-10 pr-4 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                        <i class="fas fa-search absolute left-3 top-2.5 md:top-3 text-gray-400 text-sm"></i>
                    </div>
                    
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 sm:flex-none bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-xs md:text-sm font-semibold">
                            <i class="fas fa-search mr-1"></i>Cari
                        </button>
                        <a href="{{ route('guru.subjects.index') }}" class="flex-1 sm:flex-none bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg text-xs md:text-sm font-semibold text-center">
                            <i class="fas fa-redo mr-1"></i>Reset
                        </a>
                    </div>
                </div>
            </form>

            <div>
                <button onclick="openModal('modalCreateSubject')" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 md:px-6 py-2.5 md:py-2 rounded-lg text-sm md:text-base font-semibold">
                    <i class="fas fa-plus mr-2"></i>Tambah Mata Pelajaran
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
                            <strong>Tips:</strong> Tambahkan mata pelajaran yang Anda ampu. Mata pelajaran ini akan muncul sebagai pilihan dropdown saat mengisi jurnal dan tujuan pembelajaran.
                        </p>
                    </div>
                </div>
                <div class="ml-4">
                    <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-bold">
                        {{ $subjects->total() }} Mapel
                    </span>
                </div>
            </div>
        </div>

        <!-- Table / Cards -->
        <!-- Desktop Table View -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b">
                        <th class="text-left py-3 px-4 font-semibold text-gray-700 w-12">No</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Mata Pelajaran</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-700 w-24">Status</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-700 w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subjects as $index => $subject)
                        <tr class="border-b hover:bg-gray-50 subject-row" data-subject-id="{{ $subject->id }}">
                            <td class="py-3 px-4">{{ $subjects->firstItem() + $index }}</td>
                            <td class="py-3 px-4">
                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-medium">
                                    {{ $subject->name }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($subject->is_active)
                                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-semibold">
                                        Aktif
                                    </span>
                                @else
                                    <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-semibold">
                                        Non-aktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                <button onclick="openEditSubjectModal({{ $subject->id }})" class="text-blue-600 hover:text-blue-800 mr-3" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                
                                <form action="{{ route('guru.subjects.destroy', $subject->id) }}"
                                    method="POST"
                                    class="inline delete-subject-form"
                                    data-id="{{ $subject->id }}"
                                    data-name="{{ $subject->name }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-800"
                                            title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-12 text-gray-500">
                                <i class="fas fa-book text-5xl mb-4 text-gray-300"></i>
                                <p class="text-lg mb-2">Belum ada Mata Pelajaran</p>
                                <button onclick="openModal('modalCreateSubject')" class="text-blue-600 hover:underline">
                                    <i class="fas fa-plus-circle mr-1"></i>Tambah mata pelajaran pertama
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile/Tablet Card View -->
        <div class="lg:hidden space-y-4">
            @forelse($subjects as $index => $subject)
                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm subject-card" data-subject-id="{{ $subject->id }}">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-semibold">
                                {{ $subject->name }}
                            </span>
                            @if($subject->is_active)
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-semibold">
                                    Aktif
                                </span>
                            @else
                                <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-semibold">
                                    Non-aktif
                                </span>
                            @endif
                        </div>
                        <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs font-semibold">
                            #{{ $subjects->firstItem() + $index }}
                        </span>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2 pt-3 border-t border-gray-200">
                        <button onclick="openEditSubjectModal({{ $subject->id }})" 
                                class="flex-1 bg-blue-50 hover:bg-blue-100 text-blue-600 px-4 py-2 rounded-lg text-sm font-semibold transition">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </button>
                        
                        <form action="{{ route('guru.subjects.destroy', $subject->id) }}"
                            method="POST"
                            class="flex-1 delete-subject-form"
                            data-id="{{ $subject->id }}"
                            data-name="{{ $subject->name }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-full bg-red-50 hover:bg-red-100 text-red-600 px-4 py-2 rounded-lg text-sm font-semibold transition">
                                <i class="fas fa-trash mr-1"></i>Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 text-gray-500 bg-gray-50 rounded-lg">
                    <i class="fas fa-book text-5xl mb-4 text-gray-300"></i>
                    <p class="text-base mb-3">Belum ada Mata Pelajaran</p>
                    <button onclick="openModal('modalCreateSubject')" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-semibold">
                        <i class="fas fa-plus-circle mr-2"></i>Tambah mata pelajaran pertama
                    </button>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $subjects->appends(request()->query())->links() }}
        </div>
    </div>

    <!-- Modal Create Subject -->
    <x-modal id="modalCreateSubject" title="Tambah Mata Pelajaran" size="max-w-lg">
        <form action="{{ route('guru.subjects.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Nama Mata Pelajaran <span class="text-red-500">*</span></label>
                    <input 
                        type="text" 
                        name="name" 
                        value="{{ old('name') }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        placeholder="Contoh: Matematika"
                        required
                    >
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal('modalCreateSubject')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-semibold">
                    Batal
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
            </div>
        </form>
    </x-modal>

    <!-- Modal Edit Subject -->
    <x-modal id="modalEditSubject" title="Edit Mata Pelajaran" size="max-w-lg">
        <form id="formEditSubject" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Nama Mata Pelajaran <span class="text-red-500">*</span></label>
                    <input 
                        type="text" 
                        name="name" 
                        id="edit_name"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        required
                    >
                </div>
                
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" id="edit_is_active" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-gray-700">Aktif</span>
                    </label>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal('modalEditSubject')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-semibold">
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
        const subjectData = @json($subjects->items());
        
        function openEditSubjectModal(subjectId) {
            const subject = subjectData.find(s => s.id === subjectId);
            if (!subject) return;
            
            document.getElementById('formEditSubject').action = `/guru/subjects/${subjectId}`;
            document.getElementById('edit_name').value = subject.name;
            document.getElementById('edit_is_active').checked = subject.is_active;
            
            openModal('modalEditSubject');
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Handle delete subject
            document.querySelectorAll('.delete-subject-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const subjectId = this.dataset.id;
                    const subjectName = this.dataset.name;

                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: `Mata pelajaran "${subjectName}" akan dihapus permanen`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, hapus',
                        cancelButtonText: 'Batal'
                    }).then(result => {
                        if (!result.isConfirmed) return;

                        fetch(this.action, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': this.querySelector('input[name="_token"]').value,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(res => {
                            if (res.status === 401) {
                                throw { serverMessage: 'Sesi login telah berakhir. Silakan login kembali.', reload: true };
                            }
                            if (res.status === 419) {
                                throw { serverMessage: 'Sesi telah berakhir. Silakan refresh halaman dan coba lagi.', reload: true };
                            }
                            return res.json().then(data => {
                                if (!res.ok) {
                                    throw { serverMessage: data.message || 'Gagal menghapus mata pelajaran' };
                                }
                                return data;
                            }).catch(parseError => {
                                if (parseError.serverMessage) throw parseError;
                                throw { serverMessage: 'Terjadi kesalahan server. Silakan refresh halaman.' };
                            });
                        })
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
                                Swal.fire('Gagal', data.message || 'Gagal menghapus mata pelajaran', 'error');
                            }
                        })
                        .catch((error) => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: error.serverMessage || 'Terjadi kesalahan server. Silakan refresh halaman.',
                                showConfirmButton: true,
                                confirmButtonColor: '#ef4444',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                if (error.reload) window.location.reload();
                            });
                        });
                    });
                });
            });
        });
    </script>
</x-layout>
