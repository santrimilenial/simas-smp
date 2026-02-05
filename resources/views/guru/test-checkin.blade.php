<x-layout>
    <x-slot name="title">Test Check-in</x-slot>
    <x-slot name="header">Test Manual Check-in</x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-2xl font-bold mb-6">Test Form Absensi Manual</h2>
            
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    <p class="font-semibold">Error:</p>
                    <ul class="list-disc ml-5 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('guru.attendance.checkin') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-gray-700 font-semibold mb-3">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="present">Hadir</option>
                        <option value="permission">Izin</option>
                        <option value="sick">Sakit</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Alasan (untuk Izin/Sakit)</label>
                    <textarea name="reason" class="w-full px-4 py-2 border border-gray-300 rounded-lg" rows="4" placeholder="Jelaskan alasan Anda..."></textarea>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Catatan (Opsional)</label>
                    <textarea name="notes" class="w-full px-4 py-2 border border-gray-300 rounded-lg" rows="3" placeholder="Catatan tambahan..."></textarea>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold">
                    Submit
                </button>
            </form>
        </div>

        <div class="mt-8">
            <h3 class="text-xl font-bold mb-4">Data Attendance Hari Ini</h3>
            <div class="bg-white rounded-xl shadow p-6">
                @php
                    $todayAttendance = auth()->user()->todayAttendance();
                @endphp
                
                @if ($todayAttendance)
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-gray-600">Status</p>
                            <p class="text-2xl font-bold">{{ $todayAttendance->status_label }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Alasan</p>
                            <p class="text-xl">{{ $todayAttendance->reason ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Check-in</p>
                            <p class="text-xl">{{ $todayAttendance->formatted_check_in ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Catatan</p>
                            <p class="text-xl">{{ $todayAttendance->notes ?? '-' }}</p>
                        </div>
                    </div>
                @else
                    <p class="text-gray-600">Belum ada data absensi hari ini</p>
                @endif
            </div>
        </div>
    </div>
</x-layout>
