<x-layout>
    <x-slot name="title">Detail Slip Gaji</x-slot>
    <x-slot name="header">Detail Slip Gaji</x-slot>

    <div class="p-4 md:p-8">
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="mb-6 flex justify-between items-center">
                <a href="{{ route('bendahara.slip-gaji.index') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Slip Gaji
                </a>
                <a href="{{ route('bendahara.slip-gaji.print', $slipGaji) }}" 
                   class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                    <i class="fas fa-file-pdf mr-2"></i>Download PDF
                </a>
            </div>

            <!-- Slip Gaji Card -->
            <div class="max-w-2xl mx-auto">
                <div class="border-2 border-gray-300 rounded-lg overflow-hidden">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-green-600 to-emerald-600 text-white p-6 text-center">
                        <h2 class="text-2xl font-bold mb-1">SLIP GAJI</h2>
                        <p class="text-green-100">Periode: {{ $slipGaji->period }}</p>
                    </div>

                    <!-- Content -->
                    <div class="p-6">
                        <!-- Teacher Info -->
                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <h3 class="text-sm font-semibold text-gray-600 mb-3">DATA GURU</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500">Nama Lengkap</p>
                                    <p class="font-semibold text-gray-800">{{ $slipGaji->user->name }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">NIY</p>
                                    <p class="font-semibold text-gray-800">{{ $slipGaji->user->niy }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Email</p>
                                    <p class="font-semibold text-gray-800">{{ $slipGaji->user->email }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Status Slip</p>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                        @if($slipGaji->status === 'draft') bg-yellow-100 text-yellow-800
                                        @elseif($slipGaji->status === 'approved') bg-blue-100 text-blue-800
                                        @else bg-green-100 text-green-800
                                        @endif">
                                        {{ $slipGaji->status_label }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Salary Details -->
                        <div class="space-y-4">
                            <h3 class="text-sm font-semibold text-gray-600">RINCIAN GAJI</h3>
                            
                            <div class="flex justify-between items-center py-3 border-b">
                                <span class="text-gray-600">Total Jam Mengajar</span>
                                <span class="font-bold text-purple-700">{{ $slipGaji->total_teaching_hours }} Jam</span>
                            </div>
                            
                            <div class="flex justify-between items-center py-3 border-b">
                                <span class="text-gray-600">Rate per Jam</span>
                                <span class="font-semibold text-gray-800">{{ $slipGaji->formatted_rate }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center py-4 bg-green-50 rounded-lg px-4">
                                <span class="text-lg font-semibold text-gray-800">TOTAL GAJI</span>
                                <span class="text-2xl font-bold text-green-700">{{ $slipGaji->formatted_total }}</span>
                            </div>
                        </div>

                        <!-- Timestamps -->
                        <div class="mt-6 pt-4 border-t text-sm text-gray-500">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p>Dibuat oleh: {{ $slipGaji->creator->name }}</p>
                                    <p>Tanggal: {{ $slipGaji->created_at->isoFormat('D MMMM YYYY, HH:mm') }}</p>
                                </div>
                                @if($slipGaji->approved_at)
                                <div>
                                    <p>Disetujui: {{ $slipGaji->approved_at->isoFormat('D MMMM YYYY, HH:mm') }}</p>
                                </div>
                                @endif
                                @if($slipGaji->paid_at)
                                <div>
                                    <p>Dibayar: {{ $slipGaji->paid_at->isoFormat('D MMMM YYYY, HH:mm') }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex justify-center gap-4">
                    @if($slipGaji->status === 'draft')
                        <form action="{{ route('bendahara.slip-gaji.update-status', $slipGaji) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="approved">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition">
                                <i class="fas fa-check mr-2"></i>Setujui Slip Gaji
                            </button>
                        </form>
                    @elseif($slipGaji->status === 'approved')
                        <form action="{{ route('bendahara.slip-gaji.update-status', $slipGaji) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="paid">
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-semibold transition">
                                <i class="fas fa-money-bill-wave mr-2"></i>Tandai Sudah Dibayar
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layout>
