<x-layout>
    <x-slot name="title">Jurnal Mengajar</x-slot>
    <x-slot name="header">Jurnal Mengajar Saya</x-slot>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <!-- Header Actions -->
        <div class="flex flex-col gap-4 mb-6">
            <!-- Search & Filter -->
            <form method="GET" action="{{ route('guru.jurnal.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    <div class="relative">
                        <input 
                            type="text" 
                            name="search"
                            value="{{ $search }}"
                            placeholder="Cari mapel, kelas, materi..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    
                    <select 
                        name="class"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="">Semua Kelas</option>
                        @foreach($classes as $class)
                            <option value="{{ $class }}" {{ $filterClass == $class ? 'selected' : '' }}>
                                {{ $class }}
                            </option>
                        @endforeach
                    </select>
                    
                    <input 
                        type="date" 
                        name="start_date"
                        value="{{ $startDate }}"
                        placeholder="Dari tanggal"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                    <input 
                        type="date" 
                        name="end_date"
                        value="{{ $endDate }}"
                        placeholder="Sampai tanggal"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>
                <div class="flex gap-2 mt-3">
                    <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm">
                        <i class="fas fa-filter mr-1"></i>Filter
                    </button>
                    <a href="{{ route('guru.jurnal.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg text-sm">
                        <i class="fas fa-redo mr-1"></i>Reset
                    </a>
                </div>
            </form>

            <div class="flex justify-end">
                <button onclick="openModal('modalCreateJurnal')" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold">
                    <i class="fas fa-plus mr-2"></i>Tambah Jurnal
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b">
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">No</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Tanggal</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Mata Pelajaran</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Kelas</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Pertemuan</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Jam</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">TP</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jurnals as $index => $jurnal)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4">{{ $jurnals->firstItem() + $index }}</td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="text-sm">{{ $jurnal->log_date->format('d M Y') }}</span>
                            </td>
                            <td class="py-3 px-4 font-medium">{{ $jurnal->subject }}</td>
                            <td class="py-3 px-4">
                                <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-sm">
                                    {{ $jurnal->class }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-sm font-semibold">
                                    #{{ $jurnal->meeting_number }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-sm">{{ $jurnal->time_slot }}</td>
                            <td class="py-3 px-4 text-sm text-gray-600">
                                {{ Str::limit($jurnal->tp, 40) }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <a href="{{ route('guru.jurnal.show', $jurnal->id) }}" class="text-green-600 hover:text-green-800 mr-3" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <button onclick="openEditJurnalModal({{ $jurnal->id }})" class="text-blue-600 hover:text-blue-800 mr-3" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                
                                {{-- DELETE BUTTON WITH SWEETALERT2 --}}
                                <form action="{{ route('guru.jurnal.destroy', $jurnal->id) }}"
                                    method="POST"
                                    class="inline delete-jurnal-form"
                                    data-id="{{ $jurnal->id }}"
                                    data-subject="{{ $jurnal->subject }}">
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
                            <td colspan="8" class="text-center py-12 text-gray-500">
                                <i class="fas fa-inbox text-5xl mb-4 text-gray-300"></i>
                                <p class="text-lg mb-2">Belum ada jurnal mengajar</p>
                                <a href="{{ route('guru.jurnal.create') }}" class="text-blue-600 hover:underline">
                                    <i class="fas fa-plus-circle mr-1"></i>Tambah jurnal pertama
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $jurnals->appends(request()->query())->links() }}
        </div>
    </div>

    <!-- Modal Create Jurnal -->
    <x-modal id="modalCreateJurnal" title="Tambah Jurnal Mengajar" size="max-w-4xl">
        <form action="{{ route('guru.jurnal.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Tahun Ajaran <span class="text-red-500">*</span></label>
                    <select 
                        name="academic_year_id" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        required
                    >
                        <option value="">-- Pilih Tahun Ajaran --</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ ($activeAcademicYear && $activeAcademicYear->id == $year->id) ? 'selected' : '' }}>
                                {{ $year->full_name }} {{ $year->is_active ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="log_date" value="{{ old('log_date', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Mata Pelajaran <span class="text-red-500">*</span></label>
                    <select 
                        name="subject" 
                        id="create_subject"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        required
                    >
                        @if($subjects->count() !== 1)
                            <option value="">-- Pilih Mata Pelajaran --</option>
                        @endif
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->name }}" {{ old('subject') == $subject->name || $subjects->count() === 1 ? 'selected' : '' }}>
                                {{ $subject->name }}
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
                    <select name="class" id="create_class" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class }}">{{ $class }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Pertemuan Ke <span class="text-red-500">*</span></label>
                    <input type="number" name="meeting_number" value="{{ old('meeting_number', 1) }}" min="1" max="100" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Jam Pelajaran <span class="text-red-500">*</span></label>
                    <input type="text" name="time_slot" value="{{ old('time_slot') }}" placeholder="Contoh: 1-2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>
            <div class="mt-6">
                <label class="block text-gray-700 font-semibold mb-2">
                    Tujuan Pembelajaran (TP) <span class="text-red-500">*</span>
                </label>
                <select 
                    name="tp" 
                    id="create_tp_select"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                    required
                    disabled
                >
                    <option value="">-- Pilih Mata Pelajaran & Kelas Terlebih Dahulu --</option>
                </select>
                <p class="text-sm text-gray-500 mt-1">
                    Belum ada TP untuk mata pelajaran ini? 
                    <a href="{{ route('guru.tp.index') }}" target="_blank" class="text-blue-600 hover:underline">
                        <i class="fas fa-external-link-alt"></i> Kelola TP
                    </a>
                </p>
            </div>
            <div class="mt-6">
                <label class="block text-gray-700 font-semibold mb-2">Materi yang Diajarkan <span class="text-red-500">*</span></label>
                <textarea name="material" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>{{ old('material') }}</textarea>
            </div>
            <div class="mt-6">
                <label class="block text-gray-700 font-semibold mb-2">Catatan Tambahan</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('notes') }}</textarea>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal('modalCreateJurnal')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-semibold">Batal</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold"><i class="fas fa-save mr-2"></i>Simpan</button>
            </div>
        </form>
    </x-modal>

    <!-- Modal Edit Jurnal -->
    <x-modal id="modalEditJurnal" title="Edit Jurnal Mengajar" size="max-w-4xl">
        <form id="formEditJurnal" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Tahun Ajaran <span class="text-red-500">*</span></label>
                    <select 
                        name="academic_year_id" 
                        id="edit_academic_year_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        required
                    >
                        <option value="">-- Pilih Tahun Ajaran --</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}">{{ $year->full_name }} {{ $year->is_active ? '(Aktif)' : '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="log_date" id="edit_log_date" max="{{ date('Y-m-d') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Mata Pelajaran <span class="text-red-500">*</span></label>
                    <select 
                        name="subject" 
                        id="edit_subject" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        required
                    >
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->name }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Kelas <span class="text-red-500">*</span></label>
                    <select name="class" id="edit_class" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class }}">{{ $class }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Pertemuan Ke <span class="text-red-500">*</span></label>
                    <input type="number" name="meeting_number" id="edit_meeting_number" min="1" max="100" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Jam Pelajaran <span class="text-red-500">*</span></label>
                    <input type="text" name="time_slot" id="edit_time_slot" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>
            <div class="mt-6">
                <label class="block text-gray-700 font-semibold mb-2">
                    Tujuan Pembelajaran (TP) <span class="text-red-500">*</span>
                </label>
                <select 
                    name="tp" 
                    id="edit_tp_select"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                    required
                >
                    <option value="">-- Pilih TP --</option>
                </select>
            </div>
            <div class="mt-6">
                <label class="block text-gray-700 font-semibold mb-2">Materi yang Diajarkan <span class="text-red-500">*</span></label>
                <textarea name="material" id="edit_material" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
            </div>
            <div class="mt-6">
                <label class="block text-gray-700 font-semibold mb-2">Catatan Tambahan</label>
                <textarea name="notes" id="edit_notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal('modalEditJurnal')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-semibold">Batal</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold"><i class="fas fa-save mr-2"></i>Update</button>
            </div>
        </form>
    </x-modal>

   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const jurnalData = @json($jurnals->items());
        
        // Function untuk load TP berdasarkan mata pelajaran dan kelas
        async function loadTPBySubjectAndClass(subject, classValue, selectElementId, selectedValue = '') {
            const selectElement = document.getElementById(selectElementId);
            
            if (!subject || !classValue) {
                selectElement.innerHTML = '<option value="">-- Pilih Mata Pelajaran & Kelas Terlebih Dahulu --</option>';
                selectElement.disabled = true;
                return;
            }
            
            try {
                const response = await fetch(`/guru/tp/by-subject?subject=${encodeURIComponent(subject)}&class=${encodeURIComponent(classValue)}`);
                const tps = await response.json();
                
                selectElement.innerHTML = '<option value="">-- Pilih TP --</option>';
                
                if (tps.length === 0) {
                    selectElement.innerHTML += `<option value="" disabled>Belum ada TP untuk ${subject} - ${classValue}</option>`;
                } else {
                    tps.forEach(tp => {
                        const option = document.createElement('option');
                        option.value = tp.description;
                        option.textContent = tp.description;
                        if (selectedValue && tp.description === selectedValue) {
                            option.selected = true;
                        }
                        selectElement.appendChild(option);
                    });
                }
                
                selectElement.disabled = false;
            } catch (error) {
                console.error('Error loading TP:', error);
                selectElement.innerHTML = '<option value="">Error loading TP</option>';
                selectElement.disabled = true;
            }
        }
        
        // Helper function untuk trigger load TP pada form create
        function triggerCreateTPLoad() {
            const subject = document.getElementById('create_subject')?.value || '';
            const classValue = document.getElementById('create_class')?.value || '';
            loadTPBySubjectAndClass(subject, classValue, 'create_tp_select');
        }
        
        // Helper function untuk trigger load TP pada form edit
        function triggerEditTPLoad(selectedValue = '') {
            const subject = document.getElementById('edit_subject')?.value || '';
            const classValue = document.getElementById('edit_class')?.value || '';
            loadTPBySubjectAndClass(subject, classValue, 'edit_tp_select', selectedValue);
        }
        
        // Event listener untuk form create dan edit
        document.addEventListener('DOMContentLoaded', function() {
            // Form Create - subject change
            const createSubject = document.getElementById('create_subject');
            if (createSubject) {
                createSubject.addEventListener('change', triggerCreateTPLoad);
            }
            
            // Form Create - class change
            const createClass = document.getElementById('create_class');
            if (createClass) {
                createClass.addEventListener('change', triggerCreateTPLoad);
            }
            
            // Form Edit - subject change
            const editSubject = document.getElementById('edit_subject');
            if (editSubject) {
                editSubject.addEventListener('change', function() {
                    triggerEditTPLoad();
                });
            }
            
            // Form Edit - class change
            const editClass = document.getElementById('edit_class');
            if (editClass) {
                editClass.addEventListener('change', function() {
                    triggerEditTPLoad();
                });
            }
            
            // Auto open create modal jika redirect dari route create
            @if(session('openCreateModal'))
                openModal('modalCreateJurnal');
            @endif
        });
        
       function openEditJurnalModal(jurnalId) {
        const jurnal = jurnalData.find(j => j.id === jurnalId);
        if (!jurnal) return;
        
        document.getElementById('formEditJurnal').action = `/guru/jurnal/${jurnalId}`;
        document.getElementById('edit_academic_year_id').value = jurnal.academic_year_id || '';
        document.getElementById('edit_log_date').value = jurnal.log_date_formatted;
        document.getElementById('edit_subject').value = jurnal.subject;
        document.getElementById('edit_class').value = jurnal.class;
        document.getElementById('edit_meeting_number').value = jurnal.meeting_number;
        document.getElementById('edit_time_slot').value = jurnal.time_slot;
        document.getElementById('edit_material').value = jurnal.material;
        document.getElementById('edit_notes').value = jurnal.notes || '';
        
        // Load TP untuk subject dan class yang dipilih, dan set nilai TP yang sudah ada
        loadTPBySubjectAndClass(jurnal.subject, jurnal.class, 'edit_tp_select', jurnal.tp);
        
        openModal('modalEditJurnal');
    }

        document.addEventListener('DOMContentLoaded', function () {
            // Handle delete jurnal
            document.querySelectorAll('.delete-jurnal-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const jurnalId = this.dataset.id;
                    const jurnalSubject = this.dataset.subject;
                    const row = this.closest('tr');
                    const formAction = this.action;
                    const csrfToken = this.querySelector('input[name="_token"]').value;

                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: `Jurnal "${jurnalSubject}" akan dihapus permanen`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, hapus',
                        cancelButtonText: 'Batal'
                    }).then(result => {
                        if (!result.isConfirmed) return;

                        fetch(formAction, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: '_method=DELETE&_token=' + csrfToken
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
                                    throw { serverMessage: data.message || 'Gagal menghapus jurnal' };
                                }
                                return data;
                            }).catch(parseError => {
                                if (parseError.serverMessage) throw parseError;
                                throw { serverMessage: 'Terjadi kesalahan server. Silakan refresh halaman.' };
                            });
                        })
                        .then(data => {
                            if (data.success) {
                                row.remove();

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: data.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire('Gagal', data.message || 'Gagal menghapus jurnal', 'error');
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