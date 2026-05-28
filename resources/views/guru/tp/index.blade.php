<x-layout>
    <x-slot name="title">Tujuan Pembelajaran</x-slot>
    <x-slot name="header">Kelola Tujuan Pembelajaran (TP)</x-slot>

    <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
        <!-- Header Actions -->
        <div class="flex flex-col gap-3 md:gap-4 mb-4 md:mb-6">
            <!-- Search & Filter -->
            <form method="GET" action="{{ route('guru.tp.index') }}">
                <div class="flex flex-col gap-2 md:gap-3">
                    <div class="relative">
                        <input 
                            type="text" 
                            name="search"
                            value="{{ $search }}"
                            placeholder="Cari TP atau mata pelajaran..."
                            class="w-full pl-10 pr-4 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                        <i class="fas fa-search absolute left-3 top-2.5 md:top-3 text-gray-400 text-sm"></i>
                    </div>
                    
                    <select 
                        name="subject"
                        class="px-4 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="">Semua Mata Pelajaran</option>
                        @foreach($subjects as $subj)
                            <option value="{{ $subj->name }}" {{ $subject == $subj->name ? 'selected' : '' }}>
                                {{ $subj->name }}
                            </option>
                        @endforeach
                    </select>

                    <select 
                        name="class"
                        class="px-4 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="">Semua Kelas</option>
                        @foreach($classes as $cls)
                            <option value="{{ $cls }}" {{ $class == $cls ? 'selected' : '' }}>
                                {{ $cls }}
                            </option>
                        @endforeach
                    </select>
                    
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 md:flex-none bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-xs md:text-sm font-semibold">
                            <i class="fas fa-filter mr-1"></i>Filter
                        </button>
                        <a href="{{ route('guru.tp.index') }}" class="flex-1 md:flex-none bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg text-xs md:text-sm font-semibold text-center">
                            <i class="fas fa-redo mr-1"></i>Reset
                        </a>
                    </div>
                </div>
            </form>

            <div>
                <button onclick="openModal('modalCreateTP')" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 md:px-6 py-2.5 md:py-2 rounded-lg text-sm md:text-base font-semibold">
                    <i class="fas fa-plus mr-2"></i>Tambah Tujuan Pembelajaran
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
                            <strong>Tips:</strong> Tambahkan TP untuk setiap mata pelajaran yang Anda ampu. TP ini akan muncul sebagai pilihan dropdown saat Anda mengisi jurnal mengajar.
                        </p>
                    </div>
                </div>
                <div class="ml-4">
                    <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-bold">
                        {{ $tps->total() }} TP
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
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Kelas</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Tujuan Pembelajaran</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-700 w-24">Status</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-700 w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tps as $index => $tp)
                        <tr class="border-b hover:bg-gray-50 tp-row" data-tp-id="{{ $tp->id }}">
                            <td class="py-3 px-4">{{ $tps->firstItem() + $index }}</td>
                            <td class="py-3 px-4">
                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-medium">
                                    {{ $tp->subject }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-sm font-medium">
                                    {{ $tp->class ?? '-' }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <p class="text-gray-700">{{ $tp->description }}</p>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($tp->is_active)
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
                                <button onclick="openEditTPModal({{ $tp->id }})" class="text-blue-600 hover:text-blue-800 mr-3" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                
                                <form action="{{ route('guru.tp.destroy', $tp->id) }}"
                                    method="POST"
                                    class="inline delete-tp-form"
                                    data-id="{{ $tp->id }}"
                                    data-subject="{{ $tp->subject }}">
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
                            <td colspan="6" class="text-center py-12 text-gray-500">
                                <i class="fas fa-inbox text-5xl mb-4 text-gray-300"></i>
                                <p class="text-lg mb-2">Belum ada Tujuan Pembelajaran</p>
                                <button onclick="openModal('modalCreateTP')" class="text-blue-600 hover:underline">
                                    <i class="fas fa-plus-circle mr-1"></i>Tambah TP pertama
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile/Tablet Card View -->
        <div class="lg:hidden space-y-4">
            @forelse($tps as $index => $tp)
                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm tp-card" data-tp-id="{{ $tp->id }}">
                    <!-- Header -->
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2 flex-wrap">
                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    {{ $tp->subject }}
                                </span>
                                <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    {{ $tp->class ?? '-' }}
                                </span>
                                @if($tp->is_active)
                                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-semibold">
                                        Aktif
                                    </span>
                                @else
                                    <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-semibold">
                                        Non-aktif
                                    </span>
                                @endif
                            </div>
                        </div>
                        <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs font-semibold">
                            #{{ $tps->firstItem() + $index }}
                        </span>
                    </div>

                    <!-- Content -->
                    <div class="mb-3">
                        <p class="text-sm text-gray-600 font-semibold mb-1">Tujuan Pembelajaran:</p>
                        <p class="text-gray-800 text-sm leading-relaxed">{{ $tp->description }}</p>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2 pt-3 border-t border-gray-200">
                        <button onclick="openEditTPModal({{ $tp->id }})" 
                                class="flex-1 bg-blue-50 hover:bg-blue-100 text-blue-600 px-4 py-2 rounded-lg text-sm font-semibold transition">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </button>
                        
                        <form action="{{ route('guru.tp.destroy', $tp->id) }}"
                            method="POST"
                            class="flex-1 delete-tp-form"
                            data-id="{{ $tp->id }}"
                            data-subject="{{ $tp->subject }}">
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
                    <i class="fas fa-inbox text-5xl mb-4 text-gray-300"></i>
                    <p class="text-base mb-3">Belum ada Tujuan Pembelajaran</p>
                    <button onclick="openModal('modalCreateTP')" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-semibold">
                        <i class="fas fa-plus-circle mr-2"></i>Tambah TP pertama
                    </button>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $tps->appends(request()->query())->links() }}
        </div>
    </div>

    <!-- Modal Create TP -->
    <x-modal id="modalCreateTP" title="Tambah Tujuan Pembelajaran" size="max-w-2xl">
        <form action="{{ route('guru.tp.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Mata Pelajaran <span class="text-red-500">*</span></label>
                    <select 
                        name="subject" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        required
                    >
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        @foreach($subjects as $subj)
                            <option value="{{ $subj->name }}" {{ old('subject') == $subj->name ? 'selected' : '' }}>
                                {{ $subj->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-sm text-gray-500 mt-1">
                        Belum ada mata pelajaran? 
                        <a href="{{ route('guru.subjects.index') }}" target="_blank" class="text-blue-600 hover:underline">
                            <i class="fas fa-external-link-alt"></i> Kelola Mata Pelajaran
                        </a>
                    </p>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Kelas <span class="text-red-500">*</span></label>
                    <select 
                        name="class" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        required
                    >
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($classes as $cls)
                            <option value="{{ $cls }}" {{ old('class') == $cls ? 'selected' : '' }}>
                                {{ $cls }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Tujuan Pembelajaran <span class="text-red-500">*</span></label>
                    <textarea 
                        name="description" 
                        rows="4" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        placeholder="Contoh: Peserta didik mampu menyelesaikan sistem persamaan linear dua variabel"
                        required
                    >{{ old('description') }}</textarea>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal('modalCreateTP')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-semibold">
                    Batal
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
            </div>
        </form>
    </x-modal>

    <!-- Modal Edit TP -->
    <x-modal id="modalEditTP" title="Edit Tujuan Pembelajaran" size="max-w-2xl">
        <form id="formEditTP" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Mata Pelajaran <span class="text-red-500">*</span></label>
                    <select 
                        name="subject" 
                        id="edit_subject"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        required
                    >
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        @foreach($subjects as $subj)
                            <option value="{{ $subj->name }}">{{ $subj->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Kelas <span class="text-red-500">*</span></label>
                    <select 
                        name="class" 
                        id="edit_class"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        required
                    >
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($classes as $cls)
                            <option value="{{ $cls }}">{{ $cls }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Tujuan Pembelajaran <span class="text-red-500">*</span></label>
                    <textarea 
                        name="description" 
                        id="edit_description"
                        rows="4" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        required
                    ></textarea>
                </div>
                
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" id="edit_is_active" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-gray-700">Aktif</span>
                    </label>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal('modalEditTP')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-semibold">
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
        const tpData = @json($tps->items());
        
        function openEditTPModal(tpId) {
            const tp = tpData.find(t => t.id === tpId);
            if (!tp) return;
            
            document.getElementById('formEditTP').action = `/guru/tp/${tpId}`;
            document.getElementById('edit_subject').value = tp.subject;
            document.getElementById('edit_class').value = tp.class || '';
            document.getElementById('edit_description').value = tp.description;
            document.getElementById('edit_is_active').checked = tp.is_active;
            
            openModal('modalEditTP');
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Handle delete TP
            document.querySelectorAll('.delete-tp-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const tpId = this.dataset.id;
                    const tpSubject = this.dataset.subject;
                    
                    // Cari parent container (tr untuk desktop atau div untuk mobile)
                    const container = document.querySelector(`.tp-row[data-tp-id="${tpId}"]`) || 
                                     document.querySelector(`.tp-card[data-tp-id="${tpId}"]`);

                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: `TP untuk "${tpSubject}" akan dihapus permanen`,
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
                                    throw { serverMessage: data.message || 'Gagal menghapus TP' };
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
                                    // Reload halaman untuk sync data dengan database
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire('Gagal', data.message || 'Gagal menghapus TP', 'error');
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
