<x-layout>
    <x-slot name="title">Detail Jurnal Mengajar</x-slot>
    <x-slot name="header">Detail Jurnal Mengajar</x-slot>

    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('guru.jurnal.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-800">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Daftar Jurnal
            </a>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Header with gradient -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-6">
                <div>
                    <h2 class="text-2xl font-bold mb-2">{{ $jurnal->subject }}</h2>
                    <div class="flex flex-wrap gap-4 text-blue-100">
                        @if($jurnal->academicYear)
                        <span class="flex items-center">
                            <i class="fas fa-graduation-cap mr-2"></i>
                            {{ $jurnal->academicYear->full_name }}
                        </span>
                        @endif
                        <span class="flex items-center">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            {{ $jurnal->log_date->isoFormat('dddd, D MMMM YYYY') }}
                        </span>
                        <span class="flex items-center">
                            <i class="fas fa-clock mr-2"></i>
                            {{ $jurnal->time_slot }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6 space-y-6">
                <!-- Tahun Ajaran Info -->
                @if($jurnal->academicYear)
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg p-4 border border-indigo-200">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-calendar-check text-white"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600">Tahun Ajaran</p>
                            <p class="text-lg font-bold text-indigo-700">{{ $jurnal->academicYear->full_name }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Info Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-users text-white"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600">Kelas</p>
                                <p class="text-lg font-bold text-gray-800">{{ $jurnal->class }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-50 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-hashtag text-white"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600">Pertemuan</p>
                                <p class="text-lg font-bold text-gray-800">Pertemuan Ke-{{ $jurnal->meeting_number }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tujuan Pembelajaran -->
                <div class="border-l-4 border-purple-500 bg-purple-50 rounded-r-lg p-4">
                    <h3 class="font-bold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-bullseye text-purple-600 mr-2"></i>
                        Tujuan Pembelajaran (TP)
                    </h3>
                    <p class="text-gray-700 leading-relaxed">{{ $jurnal->tp }}</p>
                </div>

                <!-- Materi -->
                <div class="border-l-4 border-blue-500 bg-blue-50 rounded-r-lg p-4">
                    <h3 class="font-bold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-book text-blue-600 mr-2"></i>
                        Materi Pembelajaran
                    </h3>
                    <div class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $jurnal->material }}</div>
                </div>

                <!-- Catatan -->
                @if($jurnal->notes)
                <div class="border-l-4 border-yellow-500 bg-yellow-50 rounded-r-lg p-4">
                    <h3 class="font-bold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-sticky-note text-yellow-600 mr-2"></i>
                        Catatan Tambahan
                    </h3>
                    <p class="text-gray-700 leading-relaxed">{{ $jurnal->notes }}</p>
                </div>
                @endif

                <!-- Metadata -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Dibuat:</span>
                            <span class="font-semibold text-gray-800 ml-2">
                                {{ $jurnal->created_at->isoFormat('D MMM YYYY, HH:mm') }}
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-600">Terakhir diubah:</span>
                            <span class="font-semibold text-gray-800 ml-2">
                                {{ $jurnal->updated_at->isoFormat('D MMM YYYY, HH:mm') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Script untuk fungsi lain jika diperlukan
    </script>
    @endpush
</x-layout>
