<x-layout>
    <x-slot name="title">Daftar Staff</x-slot>
    <x-slot name="header">Daftar Staff</x-slot>
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Daftar Staff</h2>
        <button onclick="openCreateModal()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition">
            <i class="fas fa-plus mr-2"></i>Tambah Staff
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. HP</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terdaftar</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($staff as $s)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $s->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $s->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $s->phone ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $s->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('admin.staff.show', $s) }}" class="text-blue-600 hover:text-blue-900" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button onclick="openEditModal({{ $s->id }}, '{{ $s->name }}', '{{ $s->email }}', '{{ $s->phone }}')" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteStaff({{ $s->id }}, '{{ $s->name }}')" class="text-red-600 hover:text-red-900" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data staff</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $staff->links() }}
    </div>
</div>

<!-- Modal Create Staff -->
<x-modal id="modalCreateStaff" title="Tambah Staff Baru" size="max-w-2xl">
    <form action="{{ route('admin.staff.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">No. HP</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" autocomplete="tel">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Password <span class="text-red-500">*</span></label>
                <input type="password" name="password" id="create_staff_password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" autocomplete="new-password" required>
                <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
                <p id="create_staff_password_warning" class="text-xs text-red-600 mt-1 hidden">
                    <i class="fas fa-exclamation-triangle mr-1"></i>Password harus minimal 8 karakter!
                </p>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Konfirmasi Password <span class="text-red-500">*</span></label>
                <input type="password" name="password_confirmation" id="create_staff_password_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" autocomplete="new-password" required>
                <p id="create_staff_password_match_warning" class="text-xs text-red-600 mt-1 hidden">
                    <i class="fas fa-exclamation-triangle mr-1"></i>Password tidak cocok!
                </p>
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-6">
            <button type="button" onclick="closeModal('modalCreateStaff')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-semibold">
                Batal
            </button>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold">
                <i class="fas fa-save mr-2"></i>Simpan
            </button>
        </div>
    </form>
</x-modal>

<!-- Modal Edit Staff -->
<x-modal id="modalEditStaff" title="Edit Staff" size="max-w-2xl">
    <form id="formEditStaff" method="POST">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="edit_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" id="edit_email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">No. HP</label>
                <input type="text" name="phone" id="edit_phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" autocomplete="tel">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Password Baru</label>
                <input type="password" name="password" id="edit_staff_password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" autocomplete="new-password">
                <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah (minimal 8 karakter)</p>
                <p id="edit_staff_password_warning" class="text-xs text-red-600 mt-1 hidden">
                    <i class="fas fa-exclamation-triangle mr-1"></i>Password harus minimal 8 karakter!
                </p>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="edit_staff_password_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" autocomplete="new-password">
                <p id="edit_staff_password_match_warning" class="text-xs text-red-600 mt-1 hidden">
                    <i class="fas fa-exclamation-triangle mr-1"></i>Password tidak cocok!
                </p>
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-6">
            <button type="button" onclick="closeModal('modalEditStaff')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-semibold">
                Batal
            </button>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold">
                <i class="fas fa-save mr-2"></i>Update
            </button>
        </div>
    </form>
</x-modal>

<!-- Staff Management Script - Version {{ md5(now()) }} -->
<script>
'use strict'; // Force strict mode
const staffData = @json($staff->items());

// Show success message if exists
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true
    });
@endif

function openCreateModal() {
    openModal('modalCreateStaff');
}

// Delete staff function - renamed to avoid cache
function deleteStaff(staffId, staffName) {
    Swal.fire({
        title: 'Hapus Staff?',
        html: 'Apakah Anda yakin ingin menghapus staff:<br><strong>' + staffName + '</strong>?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then(function(result) {
        if (result.isConfirmed) {
            var formElement = document.createElement('form');
            formElement.method = 'POST';
            formElement.action = '/admin/staff/' + staffId;
            
            var tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            tokenInput.value = '{{ csrf_token() }}';
            
            var methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            
            formElement.appendChild(tokenInput);
            formElement.appendChild(methodInput);
            document.body.appendChild(formElement);
            formElement.submit();
        }
    });
}

function openEditModal(id, name, email, phone) {
    const staff = staffData.find(s => s.id === id);
    if (!staff) return;
    
    document.getElementById('formEditStaff').action = `/admin/staff/${id}`;
    document.getElementById('edit_name').value = staff.name;
    document.getElementById('edit_email').value = staff.email;
    document.getElementById('edit_phone').value = staff.phone || '';
    
    openModal('modalEditStaff');
}

// Validasi password untuk create staff
const createStaffPassword = document.getElementById('create_staff_password');
const createStaffPasswordConfirmation = document.getElementById('create_staff_password_confirmation');
const createStaffPasswordWarning = document.getElementById('create_staff_password_warning');
const createStaffPasswordMatchWarning = document.getElementById('create_staff_password_match_warning');

createStaffPassword.addEventListener('input', function() {
    if (this.value.length > 0 && this.value.length < 8) {
        createStaffPasswordWarning.classList.remove('hidden');
        this.classList.add('border-red-500');
    } else {
        createStaffPasswordWarning.classList.add('hidden');
        this.classList.remove('border-red-500');
    }
    checkPasswordMatch(createStaffPassword, createStaffPasswordConfirmation, createStaffPasswordMatchWarning);
});

createStaffPasswordConfirmation.addEventListener('input', function() {
    checkPasswordMatch(createStaffPassword, createStaffPasswordConfirmation, createStaffPasswordMatchWarning);
});

// Validasi password untuk edit staff
const editStaffPassword = document.getElementById('edit_staff_password');
const editStaffPasswordConfirmation = document.getElementById('edit_staff_password_confirmation');
const editStaffPasswordWarning = document.getElementById('edit_staff_password_warning');
const editStaffPasswordMatchWarning = document.getElementById('edit_staff_password_match_warning');

editStaffPassword.addEventListener('input', function() {
    if (this.value.length > 0 && this.value.length < 8) {
        editStaffPasswordWarning.classList.remove('hidden');
        this.classList.add('border-red-500');
    } else {
        editStaffPasswordWarning.classList.add('hidden');
        this.classList.remove('border-red-500');
    }
    checkPasswordMatch(editStaffPassword, editStaffPasswordConfirmation, editStaffPasswordMatchWarning);
});

editStaffPasswordConfirmation.addEventListener('input', function() {
    checkPasswordMatch(editStaffPassword, editStaffPasswordConfirmation, editStaffPasswordMatchWarning);
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
</script>
</x-layout>
