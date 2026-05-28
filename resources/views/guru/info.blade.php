<x-layout>
    <x-slot name="title">Informasi Guru</x-slot>
    <x-slot name="header">Informasi Saya</x-slot>

    @php $user = auth()->user(); @endphp

    <div class="max-w-3xl mx-auto">
        <!-- Profile Card -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-8">
                <div class="flex items-center gap-4">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-20 h-20 rounded-full border-4 border-white/30 object-cover shadow-lg">
                    <div class="text-white">
                        <h2 class="text-xl md:text-2xl font-bold">{{ $user->name }}</h2>
                        <p class="text-blue-100 text-sm mt-1">NIY: {{ $user->niy }}</p>
                        @if($user->position)
                            <span class="inline-block bg-white/20 text-white text-xs px-3 py-1 rounded-full mt-2">{{ $user->position }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Info Grid -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama -->
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-user text-blue-500"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Nama Lengkap</p>
                            <p class="text-sm font-semibold text-gray-800 mt-0.5">{{ $user->name }}</p>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-envelope text-indigo-500"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Email</p>
                            <p class="text-sm font-semibold text-gray-800 mt-0.5">{{ $user->email }}</p>
                        </div>
                    </div>

                    <!-- No. Telepon -->
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-phone text-green-500"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">No. Telepon</p>
                            <p class="text-sm font-semibold text-gray-800 mt-0.5">{{ $user->phone ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Jabatan -->
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-briefcase text-purple-500"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Jabatan</p>
                            <p class="text-sm font-semibold text-gray-800 mt-0.5">{{ $user->position ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div class="flex items-start gap-3 md:col-span-2">
                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-map-marker-alt text-red-500"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Alamat</p>
                            <p class="text-sm font-semibold text-gray-800 mt-0.5">{{ $user->address ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Tahun Masuk -->
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-calendar-alt text-yellow-500"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Tahun Masuk</p>
                            <p class="text-sm font-semibold text-gray-800 mt-0.5">{{ $user->join_year ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Masa Kerja -->
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-clock text-orange-500"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Masa Kerja</p>
                            @if($user->join_year)
                                <p class="text-sm font-semibold text-gray-800 mt-0.5">
                                    {{ $user->years_of_service }} tahun
                                    <span class="text-xs text-gray-400 font-normal">(sejak {{ $user->join_year }})</span>
                                </p>
                            @else
                                <p class="text-sm font-semibold text-gray-800 mt-0.5">-</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Note -->
        <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4 text-sm text-blue-700">
            <i class="fas fa-info-circle mr-2"></i>
            Informasi ini dikelola oleh admin. Jika ada data yang perlu diperbarui, silakan hubungi admin sekolah.
        </div>
    </div>
</x-layout>
