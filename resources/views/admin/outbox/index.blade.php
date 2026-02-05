<x-layout>
    <x-slot name="title">Outbox Password Reset</x-slot>
    <x-slot name="header">Outbox Password Reset</x-slot>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-blue-50 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-500 rounded-full">
                        <i class="fas fa-inbox text-white"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Total</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $stats['total'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-yellow-50 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-500 rounded-full">
                        <i class="fas fa-envelope text-white"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Belum Dibaca</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $stats['unread'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-green-50 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="p-3 bg-green-500 rounded-full">
                        <i class="fas fa-envelope-open text-white"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Sudah Dibaca</p>
                        <p class="text-2xl font-bold text-green-600">{{ $stats['read'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-red-50 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="p-3 bg-red-500 rounded-full">
                        <i class="fas fa-clock text-white"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Kadaluarsa</p>
                        <p class="text-2xl font-bold text-red-600">{{ $stats['expired'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Actions -->
        <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
            <div class="flex gap-2">
                <a href="{{ route('admin.outbox.index', ['filter' => 'all']) }}" 
                   class="px-4 py-2 rounded-lg {{ $filter === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Semua
                </a>
                <a href="{{ route('admin.outbox.index', ['filter' => 'unread']) }}" 
                   class="px-4 py-2 rounded-lg {{ $filter === 'unread' ? 'bg-yellow-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Belum Dibaca
                </a>
                <a href="{{ route('admin.outbox.index', ['filter' => 'read']) }}" 
                   class="px-4 py-2 rounded-lg {{ $filter === 'read' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Sudah Dibaca
                </a>
                <a href="{{ route('admin.outbox.index', ['filter' => 'expired']) }}" 
                   class="px-4 py-2 rounded-lg {{ $filter === 'expired' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Kadaluarsa
                </a>
            </div>
            <div class="flex gap-2">
                @if($stats['unread'] > 0)
                <form action="{{ route('admin.outbox.mark-all-read') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-check-double mr-2"></i>Tandai Semua Dibaca
                    </button>
                </form>
                @endif
                @if($stats['expired'] > 0)
                <form action="{{ route('admin.outbox.cleanup') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-trash mr-2"></i>Hapus Kadaluarsa
                    </button>
                </form>
                @endif
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b">
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">No</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Guru</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Password Baru</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Direset Oleh</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Waktu Reset</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Kadaluarsa</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-700">Status</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $index => $log)
                        <tr class="border-b hover:bg-gray-50 {{ !$log->is_read && !$log->isExpired() ? 'bg-yellow-50' : '' }}">
                            <td class="py-3 px-4">{{ $logs->firstItem() + $index }}</td>
                            <td class="py-3 px-4">
                                <div class="font-medium">{{ $log->user->name ?? 'Deleted User' }}</div>
                                <div class="text-sm text-gray-500">{{ $log->user->email ?? '-' }}</div>
                            </td>
                            <td class="py-3 px-4">
                                @if($log->isExpired())
                                    <span class="text-red-500 italic">Kadaluarsa</span>
                                @elseif($log->is_read)
                                    <code class="bg-gray-100 px-2 py-1 rounded text-sm">{{ $log->decrypted_password }}</code>
                                @else
                                    <span class="text-gray-400 italic">
                                        <i class="fas fa-eye-slash mr-1"></i>Klik lihat untuk membuka
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4">{{ $log->resetByUser->name ?? 'System' }}</td>
                            <td class="py-3 px-4">
                                <div>{{ $log->created_at->format('d M Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $log->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td class="py-3 px-4">
                                @if($log->isExpired())
                                    <span class="text-red-500">{{ $log->expires_at->format('d M Y H:i') }}</span>
                                @else
                                    <span class="text-green-600">{{ $log->expires_at->format('d M Y H:i') }}</span>
                                    <div class="text-xs text-gray-500">{{ $log->expires_at->diffForHumans() }}</div>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($log->isExpired())
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        Kadaluarsa
                                    </span>
                                @elseif($log->is_read)
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        Sudah Dibaca
                                    </span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        Belum Dibaca
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    @if(!$log->is_read && !$log->isExpired())
                                    <a href="{{ route('admin.outbox.show', $log) }}" 
                                       class="bg-blue-100 hover:bg-blue-200 text-blue-700 p-2 rounded-lg" 
                                       title="Lihat Password">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @endif
                                    <form action="{{ route('admin.outbox.destroy', $log) }}" method="POST" class="inline delete-outbox-form" data-name="{{ $log->user->name ?? 'Deleted User' }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-100 hover:bg-red-200 text-red-700 p-2 rounded-lg" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-3 block"></i>
                                Tidak ada data password reset.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $logs->appends(['filter' => $filter])->links() }}
        </div>
    </div>

    <!-- Info Box -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
            <div>
                <h4 class="font-semibold text-blue-700">Informasi Keamanan</h4>
                <ul class="text-sm text-blue-600 mt-2 space-y-1">
                    <li>• Password yang direset akan otomatis kadaluarsa setelah <strong>24 jam</strong>.</li>
                    <li>• Password disimpan dengan enkripsi dan hanya dapat dilihat oleh admin.</li>
                    <li>• Segera berikan password baru kepada guru yang bersangkutan.</li>
                    <li>• Sarankan guru untuk segera mengganti password setelah login.</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle delete outbox
            document.querySelectorAll('.delete-outbox-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const userName = this.dataset.name;
                    const formEl = this;
                    
                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: `Log password reset untuk "${userName}" akan dihapus permanen`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            formEl.submit();
                        }
                    });
                });
            });
        });
    </script>
</x-layout>
