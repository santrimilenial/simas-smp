<x-layout>
    <x-slot name="title">Kelola Guru</x-slot>
    <x-slot name="header">Kelola Data Guru</x-slot>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <!-- Header Actions -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex-1 max-w-md">
                <form method="GET" action="{{ route('admin.guru.index') }}">
                    <div class="relative">
                        <input 
                            type="text" 
                            name="search"
                            value="{{ $search }}"
                            placeholder="Cari nama, email, atau NIY..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </form>
            </div>
            <button onclick="openModal('modalCreateGuru')" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold ml-4">
                <i class="fas fa-plus mr-2"></i>Tambah Guru
            </button>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b">
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">No</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Nama</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">NIY</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Email</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Total Jurnal</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gurus as $index => $guru)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4">{{ $gurus->firstItem() + $index }}</td>
                            <td class="py-3 px-4 font-medium">{{ $guru->name }}</td>
                            <td class="py-3 px-4">{{ $guru->niy }}</td>
                            <td class="py-3 px-4">{{ $guru->email }}</td>
                            <td class="py-3 px-4">
                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-semibold">
                                    {{ $guru->teaching_logs_count }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <a href="{{ route('admin.guru.show', $guru->id) }}" class="text-green-600 hover:text-green-800 mr-3" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button onclick="openEditGuruModal({{ $guru->id }})" class="text-blue-600 hover:text-blue-800 mr-3" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                
                                {{-- RESET PASSWORD BUTTON --}}
                                <button onclick="confirmResetPassword({{ $guru->id }}, '{{ $guru->name }}', '{{ $guru->niy }}')" 
                                        class="text-yellow-600 hover:text-yellow-800 mr-3" 
                                        title="Reset Password">
                                    <i class="fas fa-key"></i>
                                </button>
                                
                                {{-- DELETE BUTTON WITH SWEETALERT2 --}}
                                <form action="{{ route('admin.guru.destroy', $guru->id) }}" 
                                      method="POST" 
                                      id="deleteGuruForm-{{ $guru->id }}" 
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" 
                                            onclick="confirmDelete('deleteGuruForm-{{ $guru->id }}', '{{ $guru->name }} ({{ $guru->niy }})')"
                                            class="text-red-600 hover:text-red-800 transition" 
                                            title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-2"></i>
                                <p>Belum ada data guru</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $gurus->links() }}
        </div>
    </div>

    <!-- Modal Create Guru -->
    <x-modal id="modalCreateGuru" title="Tambah Guru Baru" size="max-w-3xl">
        <form action="{{ route('admin.guru.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">NIY <span class="text-red-500">*</span></label>
                    <input type="text" name="niy" value="{{ old('niy') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">No. Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" id="create_password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
                    <p id="create_password_warning" class="text-xs text-red-600 mt-1 hidden">
                        <i class="fas fa-exclamation-triangle mr-1"></i>Password harus minimal 8 karakter!
                    </p>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Konfirmasi Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" id="create_password_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <p id="create_password_match_warning" class="text-xs text-red-600 mt-1 hidden">
                        <i class="fas fa-exclamation-triangle mr-1"></i>Password tidak cocok!
                    </p>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal('modalCreateGuru')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-semibold">
                    Batal
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
            </div>
        </form>
    </x-modal>

    <!-- Modal Edit Guru -->
    <x-modal id="modalEditGuru" title="Edit Data Guru" size="max-w-3xl">
        <form id="formEditGuru" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="edit_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">NIY <span class="text-red-500">*</span></label>
                    <input type="text" name="niy" id="edit_niy" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="edit_email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">No. Telepon</label>
                    <input type="text" name="phone" id="edit_phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Password Baru</label>
                    <input type="password" name="password" id="edit_password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah (minimal 8 karakter)</p>
                    <p id="edit_password_warning" class="text-xs text-red-600 mt-1 hidden">
                        <i class="fas fa-exclamation-triangle mr-1"></i>Password harus minimal 8 karakter!
                    </p>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="edit_password_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p id="edit_password_match_warning" class="text-xs text-red-600 mt-1 hidden">
                        <i class="fas fa-exclamation-triangle mr-1"></i>Password tidak cocok!
                    </p>
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

    <script>
        const guruData = @json($gurus->items());
        
        // Validasi password untuk create guru
        const createPassword = document.getElementById('create_password');
        const createPasswordConfirmation = document.getElementById('create_password_confirmation');
        const createPasswordWarning = document.getElementById('create_password_warning');
        const createPasswordMatchWarning = document.getElementById('create_password_match_warning');

        createPassword.addEventListener('input', function() {
            if (this.value.length > 0 && this.value.length < 8) {
                createPasswordWarning.classList.remove('hidden');
                this.classList.add('border-red-500');
            } else {
                createPasswordWarning.classList.add('hidden');
                this.classList.remove('border-red-500');
            }
            checkPasswordMatch(createPassword, createPasswordConfirmation, createPasswordMatchWarning);
        });

        createPasswordConfirmation.addEventListener('input', function() {
            checkPasswordMatch(createPassword, createPasswordConfirmation, createPasswordMatchWarning);
        });

        // Validasi password untuk edit guru
        const editPassword = document.getElementById('edit_password');
        const editPasswordConfirmation = document.getElementById('edit_password_confirmation');
        const editPasswordWarning = document.getElementById('edit_password_warning');
        const editPasswordMatchWarning = document.getElementById('edit_password_match_warning');

        editPassword.addEventListener('input', function() {
            if (this.value.length > 0 && this.value.length < 8) {
                editPasswordWarning.classList.remove('hidden');
                this.classList.add('border-red-500');
            } else {
                editPasswordWarning.classList.add('hidden');
                this.classList.remove('border-red-500');
            }
            checkPasswordMatch(editPassword, editPasswordConfirmation, editPasswordMatchWarning);
        });

        editPasswordConfirmation.addEventListener('input', function() {
            checkPasswordMatch(editPassword, editPasswordConfirmation, editPasswordMatchWarning);
        });

        function checkPasswordMatch(passwordField, confirmField, warningElement) {
            if (confirmField.value.length > 0 && passwordField.value !== confirmField.value) {
                warningElement.classList.remove('hidden');
                confirmField.classList.add('border-red-500');
            } else {
                warningElement.classList.add('hidden');
                confirmField.classList.remove('border-red-500');
            }
        }
        
        function openEditGuruModal(guruId) {
            try {
                const guru = guruData.find(g => g.id === guruId);
                if (!guru) {
                    alert('Data guru tidak ditemukan!');
                    console.error('Guru dengan id', guruId, 'tidak ditemukan di guruData:', guruData);
                    return;
                }
                document.getElementById('formEditGuru').action = `/admin/guru/${guruId}`;
                document.getElementById('edit_name').value = guru.name;
                document.getElementById('edit_niy').value = guru.niy;
                document.getElementById('edit_email').value = guru.email;
                document.getElementById('edit_phone').value = guru.phone || '';
                openModal('modalEditGuru');
            } catch (e) {
                alert('Terjadi error saat membuka modal edit guru!');
                console.error('Error openEditGuruModal:', e);
            }
        }

        // Auto open modal if validation errors
        @if($errors->any())
            @if(request()->routeIs('admin.guru.store'))
                openModal('modalCreateGuru');
            @endif
        @endif

        // Confirm Reset Password
        function confirmResetPassword(guruId, guruName, guruNiy) {
            Swal.fire({
                title: 'Reset Password?',
                html: `Password <strong>${guruName}</strong> akan direset ke NIY: <strong>${guruNiy}</strong>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f59e0b',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fas fa-key mr-1"></i> Ya, Reset Password',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('resetPasswordForm-' + guruId).submit();
                }
            });
        }
    </script>

    {{-- Hidden forms for reset password --}}
    @foreach($gurus as $guru)
        <form id="resetPasswordForm-{{ $guru->id }}" 
              action="{{ route('admin.guru.reset-password', $guru->id) }}" 
              method="POST" 
              class="hidden">
            @csrf
        </form>
    @endforeach
</x-layout>