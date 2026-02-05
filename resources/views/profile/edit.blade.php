<x-layout>
    <x-slot name="header">Profil & Pengaturan</x-slot>

    <div class="space-y-6">
        <!-- Profile Photo -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-camera text-purple-500 mr-2"></i>
                Foto Profil
            </h3>
            
            <div class="flex items-center gap-6">
                <div class="relative">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" 
                         class="w-24 h-24 rounded-full object-cover border-4 border-gray-200" id="avatarPreview">
                    @if($user->avatar)
                        <form action="{{ route('profile.avatar.delete') }}" method="POST" class="absolute -top-1 -right-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs shadow-lg" 
                                    onclick="return confirm('Hapus foto profil?')">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                    @endif
                </div>
                
                <div class="flex-1">
                    <form action="{{ route('profile.avatar.update') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih foto baru</label>
                            <input type="file" name="avatar" accept="image/*" 
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                   onchange="previewImage(this)">
                            <p class="mt-1 text-xs text-gray-500">JPG, PNG, GIF maksimal 2MB</p>
                            @error('avatar')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium transition text-sm">
                            <i class="fas fa-upload mr-2"></i>Upload Foto
                        </button>
                        @if (session('status') === 'avatar-updated')
                            <span class="text-sm text-green-600 ml-2">Foto berhasil diupdate.</span>
                        @endif
                        @if (session('status') === 'avatar-deleted')
                            <span class="text-sm text-green-600 ml-2">Foto berhasil dihapus.</span>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <!-- Update Profile Information -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-user-edit text-blue-500 mr-2"></i>
                Informasi Profil
            </h3>
            <p class="text-sm text-gray-600 mb-4">Perbarui informasi profil dan alamat email akun Anda.</p>
            
            <form method="post" action="{{ route('profile.update') }}" class="space-y-4 max-w-xl">
                @csrf
                @method('patch')

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                    @if (session('status') === 'profile-updated')
                        <p class="text-sm text-green-600">Tersimpan.</p>
                    @endif
                </div>
            </form>
        </div>

        <!-- Update Password -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-lock text-green-500 mr-2"></i>
                Ubah Password
            </h3>
            <p class="text-sm text-gray-600 mb-4">Pastikan akun Anda menggunakan password yang panjang dan acak agar tetap aman.</p>
            
            <form method="post" action="{{ route('password.update') }}" class="space-y-4 max-w-xl">
                @csrf
                @method('put')

                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
                    <input type="password" id="current_password" name="current_password" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" autocomplete="current-password">
                    @error('current_password', 'updatePassword')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                    <input type="password" id="password" name="password" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" autocomplete="new-password">
                    @error('password', 'updatePassword')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" autocomplete="new-password">
                    @error('password_confirmation', 'updatePassword')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition">
                        <i class="fas fa-key mr-2"></i>Ubah Password
                    </button>
                    @if (session('status') === 'password-updated')
                        <p class="text-sm text-green-600">Password berhasil diubah.</p>
                    @endif
                </div>
            </form>
        </div>

        <!-- Account Info -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                Informasi Akun
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <span class="text-gray-500">Role:</span>
                    <span class="font-medium text-gray-800 ml-2 capitalize">{{ $user->role }}</span>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <span class="text-gray-500">NIY:</span>
                    <span class="font-medium text-gray-800 ml-2">{{ $user->niy ?? '-' }}</span>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <span class="text-gray-500">Telepon:</span>
                    <span class="font-medium text-gray-800 ml-2">{{ $user->phone ?? '-' }}</span>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <span class="text-gray-500">Terdaftar:</span>
                    <span class="font-medium text-gray-800 ml-2">{{ $user->created_at->isoFormat('D MMMM YYYY') }}</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatarPreview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-layout>
