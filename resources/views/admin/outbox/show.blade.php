<x-layout>
    <x-slot name="title">Detail Password Reset</x-slot>
    <x-slot name="header">Detail Password Reset</x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('admin.outbox.index') }}" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Outbox
                </a>
            </div>

            <!-- Guru Info -->
            <div class="border-b pb-4 mb-4">
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Informasi Guru</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Nama</p>
                        <p class="font-medium">{{ $outbox->user->name ?? 'Deleted User' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-medium">{{ $outbox->user->email ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">NIY</p>
                        <p class="font-medium">{{ $outbox->user->niy ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">No. HP</p>
                        <p class="font-medium">{{ $outbox->user->phone ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Password Info -->
            <div class="border-b pb-4 mb-4">
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Password Baru</h3>
                
                @if($outbox->isExpired())
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                        <i class="fas fa-exclamation-triangle text-red-500 text-2xl mb-2"></i>
                        <p class="text-red-600 font-medium">Password sudah kadaluarsa!</p>
                        <p class="text-sm text-red-500 mt-1">Silakan reset ulang password guru ini.</p>
                    </div>
                @else
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Password:</p>
                                <p class="text-2xl font-mono font-bold text-green-700" id="password-text">
                                    {{ $outbox->decrypted_password }}
                                </p>
                            </div>
                            <button onclick="copyPassword()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                                <i class="fas fa-copy mr-2"></i>Salin
                            </button>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Meta Info -->
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Direset oleh</p>
                    <p class="font-medium">{{ $outbox->resetByUser->name ?? 'System' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Waktu Reset</p>
                    <p class="font-medium">{{ $outbox->created_at->format('d M Y H:i') }} WIB</p>
                </div>
                <div>
                    <p class="text-gray-500">Kadaluarsa</p>
                    <p class="font-medium {{ $outbox->isExpired() ? 'text-red-600' : 'text-green-600' }}">
                        {{ $outbox->expires_at->format('d M Y H:i') }} WIB
                        @if(!$outbox->isExpired())
                            ({{ $outbox->expires_at->diffForHumans() }})
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-gray-500">Status</p>
                    @if($outbox->isExpired())
                        <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-semibold">Kadaluarsa</span>
                    @elseif($outbox->is_read)
                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-semibold">Sudah Dibaca</span>
                    @else
                        <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs font-semibold">Belum Dibaca</span>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-6 flex gap-3">
                <a href="{{ route('admin.guru.index') }}" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-center">
                    <i class="fas fa-users mr-2"></i>Ke Halaman Guru
                </a>
                <form action="{{ route('admin.outbox.destroy', $outbox) }}" method="POST" class="flex-1" onsubmit="return confirm('Yakin ingin menghapus log ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-100 hover:bg-red-200 text-red-700 px-4 py-2 rounded-lg">
                        <i class="fas fa-trash mr-2"></i>Hapus Log
                    </button>
                </form>
            </div>
        </div>

        <!-- Warning Box -->
        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-xl p-4">
            <div class="flex items-start">
                <i class="fas fa-exclamation-triangle text-yellow-500 mt-1 mr-3"></i>
                <div>
                    <h4 class="font-semibold text-yellow-700">Peringatan</h4>
                    <p class="text-sm text-yellow-600 mt-1">
                        Jangan bagikan password ini melalui media yang tidak aman. Berikan langsung kepada guru yang bersangkutan.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyPassword() {
            const password = document.getElementById('password-text').textContent.trim();
            navigator.clipboard.writeText(password).then(() => {
                alert('Password berhasil disalin!');
            }).catch(err => {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = password;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                alert('Password berhasil disalin!');
            });
        }
    </script>
</x-layout>
