<x-layout>
    <x-slot name="title">Kelola Kelas</x-slot>
    <x-slot name="header">Kelola Data Kelas</x-slot>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <!-- Header Actions -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <!-- Search & Filter -->
            <form method="GET" action="{{ route('admin.classes.index') }}" class="flex-1 w-full md:w-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="relative">
                        <input 
                            type="text" 
                            name="search"
                            value="{{ $search }}"
                            placeholder="Cari kelas..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <select 
                        name="grade_level"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        onchange="this.form.submit()"
                    >
                        <option value="">Semua Tingkat</option>
                        @foreach($gradeLevels as $level)
                            <option value="{{ $level }}" {{ $gradeLevel == $level ? 'selected' : '' }}>
                                Kelas {{ $level }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>

            <button onclick="openModal('modalCreateClass')" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold whitespace-nowrap">
                <i class="fas fa-plus mr-2"></i>Tambah Kelas
            </button>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b">
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">No</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Nama Kelas</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Tingkat</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Grup</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Jumlah Siswa</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Total Jurnal</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-700">Status</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classes as $index => $class)
                        <tr class="border-b hover:bg-gray-50" 
                            data-class-id="{{ $class->id }}"
                            data-class-name="{{ $class->name }}"
                            data-class-grade="{{ $class->grade_level }}"
                            data-class-group="{{ $class->class_group }}"
                            data-class-students="{{ $class->student_count }}"
                            data-class-order="{{ $class->order }}"
                            data-class-active="{{ $class->is_active ? 1 : 0 }}">
                            <td class="py-3 px-4">{{ $classes->firstItem() + $index }}</td>
                            <td class="py-3 px-4 font-medium">{{ $class->name }}</td>
                            <td class="py-3 px-4">Kelas {{ $class->grade_level }}</td>
                            <td class="py-3 px-4">{{ $class->class_group ?? '-' }}</td>
                            <td class="py-3 px-4">
                                <span class=" text-purple-700 px-3 py-1  text-sm font-semibold">
                                    {{ $class->student_count }} siswa
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <span class=" text-blue-700 px-3 py-1 text-sm font-semibold">
                                    {{ $class->teaching_logs_count }} jurnal
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                {{-- TOGGLE STATUS WITH SWEETALERT2 --}}
                                <form action="{{ route('admin.classes.toggle', $class->id) }}" 
                                      method="POST" 
                                      id="toggleClassForm-{{ $class->id }}"
                                      class="inline">
                                    @csrf
                                    <button type="button" 
                                            onclick="confirmToggleStatus('toggleClassForm-{{ $class->id }}', '{{ $class->name }}', {{ $class->is_active ? 'true' : 'false' }})"
                                            class="px-3 py-1 rounded-full text-sm font-semibold transition {{ $class->is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                        {{ $class->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </button>
                                </form>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <button onclick="openEditClassModal({{ $class->id }})" class="text-blue-600 hover:text-blue-800 mr-3" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                
                                {{-- DELETE BUTTON WITH SWEETALERT2 --}}
                                @if($class->teaching_logs_count == 0)
                                    <form action="{{ route('admin.classes.destroy', $class->id) }}" 
                                          method="POST" 
                                          id="deleteClassForm-{{ $class->id }}" 
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                onclick="confirmDelete('deleteClassForm-{{ $class->id }}', 'Kelas {{ $class->name }}')"
                                                class="text-red-600 hover:text-red-800 transition" 
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 cursor-not-allowed" title="Tidak bisa dihapus karena ada {{ $class->teaching_logs_count }} jurnal terkait">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 text-gray-500">
                                <i class="fas fa-inbox text-5xl mb-4 text-gray-300"></i>
                                <p class="text-lg mb-2">Belum ada data kelas</p>
                                <a href="{{ route('admin.classes.create') }}" class="text-blue-600 hover:underline">
                                    <i class="fas fa-plus-circle mr-1"></i>Tambah kelas pertama
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $classes->appends(request()->query())->links() }}
        </div>
    </div>

    <!-- Modal Create Class -->
    <x-modal id="modalCreateClass" title="Tambah Kelas Baru">
        <form action="{{ route('admin.classes.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Nama Kelas <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: 7A" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Tingkat Kelas <span class="text-red-500">*</span></label>
                    <select name="grade_level" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">-- Pilih Tingkat --</option>
                        @for($i = 7; $i <= 12; $i++)
                            <option value="{{ $i }}">Kelas {{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Grup Kelas</label>
                    <input type="text" name="class_group" value="{{ old('class_group') }}" placeholder="Contoh: A, IPA 1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class=" text-gray-700 font-semibold mb-2">Jumlah Siswa <span class="text-red-500">*</span></label>
                    <input type="number" name="student_count" value="{{ old('student_count', 0) }}" min="0" max="100" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Urutan Tampilan</label>
                    <input type="number" name="order" value="{{ old('order', 0) }}" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex items-center">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 text-blue-600 mr-2">
                        <span class="text-gray-700 font-semibold">Kelas Aktif</span>
                    </label>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal('modalCreateClass')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-semibold">Batal</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold"><i class="fas fa-save mr-2"></i>Simpan</button>
            </div>
        </form>
    </x-modal>

    <!-- Modal Edit Class -->
    <x-modal id="modalEditClass" title="Edit Data Kelas">
        <form id="formEditClass" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Nama Kelas <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="edit_name" value="{{ old('name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Tingkat Kelas <span class="text-red-500">*</span></label>
                    <select name="grade_level" id="edit_grade_level" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        @for($i = 7; $i <= 12; $i++)
                            <option value="{{ $i }}">Kelas {{ $i }}</option>
                        @endfor
                    </select>
                    @error('grade_level') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Grup Kelas</label>
                    <input type="text" name="class_group" id="edit_class_group" value="{{ old('class_group') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('class_group') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Jumlah Siswa <span class="text-red-500">*</span></label>
                    <input type="number" name="student_count" id="edit_student_count" min="0" max="100" value="{{ old('student_count') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('student_count') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Urutan Tampilan</label>
                    <input type="number" name="order" id="edit_order" min="0" value="{{ old('order') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('order') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-center">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" id="edit_is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4 text-blue-600 mr-2">
                        <span class="text-gray-700 font-semibold">Kelas Aktif</span>
                    </label>
                    @error('is_active') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal('modalEditClass')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-semibold">Batal</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold"><i class="fas fa-save mr-2"></i>Update</button>
            </div>
        </form>
    </x-modal>

    <script>
        // Store all class data from the page
        const allClasses = {
            @forelse($classes as $class)
                {{ $class->id }}: {
                    id: {{ $class->id }},
                    name: @json($class->name),
                    grade_level: @json($class->grade_level),
                    class_group: @json($class->class_group),
                    student_count: {{ $class->student_count }},
                    order: {{ $class->order }},
                    is_active: {{ $class->is_active ? 'true' : 'false' }}
                },
            @empty
            @endforelse
        };
        
        // Check if there are validation errors (old inputs)
        const hasErrors = {{ $errors->any() ? 'true' : 'false' }};
        
        if (hasErrors) {
            document.addEventListener('DOMContentLoaded', function() {
                openModal('modalEditClass');
                // Populate with old inputs
                const oldName = @json(old('name'));
                const oldGradeLevel = @json(old('grade_level'));
                const oldClassGroup = @json(old('class_group'));
                const oldStudentCount = @json(old('student_count'));
                const oldOrder = @json(old('order'));
                const oldIsActive = @json(old('is_active'));
                
                if (oldName) document.getElementById('edit_name').value = oldName;
                if (oldGradeLevel) document.getElementById('edit_grade_level').value = oldGradeLevel;
                if (oldClassGroup) document.getElementById('edit_class_group').value = oldClassGroup;
                if (oldStudentCount !== null) document.getElementById('edit_student_count').value = oldStudentCount;
                if (oldOrder !== null) document.getElementById('edit_order').value = oldOrder;
                if (oldIsActive !== null) document.getElementById('edit_is_active').checked = oldIsActive;
            });
        }
        
        function openEditClassModal(classId) {
            const classItem = allClasses[classId];
            
            if (!classItem) {
                alert('Data kelas tidak ditemukan');
                return;
            }
            
            // Set form action and populate fields
            const formEditClass = document.getElementById('formEditClass');
            formEditClass.action = `/admin/classes/${classItem.id}`;
            
            document.getElementById('edit_name').value = classItem.name || '';
            document.getElementById('edit_grade_level').value = classItem.grade_level || '';
            document.getElementById('edit_class_group').value = classItem.class_group || '';
            document.getElementById('edit_student_count').value = classItem.student_count || 0;
            document.getElementById('edit_order').value = classItem.order || 0;
            document.getElementById('edit_is_active').checked = Boolean(classItem.is_active);
            
            openModal('modalEditClass');
        }

        // Confirm Toggle Status with SweetAlert2
        function confirmToggleStatus(formId, className, isActive) {
            const action = isActive ? 'nonaktifkan' : 'aktifkan';
            const actionCap = isActive ? 'Nonaktifkan' : 'Aktifkan';
            
            Swal.fire({
                title: `${actionCap} Kelas?`,
                html: `<p class="text-gray-700">Apakah Anda yakin ingin ${action}:</p><p class="font-bold text-lg mt-2 text-gray-900">${className}</p>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: isActive ? '#f59e0b' : '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: `<i class="fas fa-${isActive ? 'toggle-off' : 'toggle-on'} mr-2"></i>Ya, ${actionCap}!`,
                cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>
</x-layout>