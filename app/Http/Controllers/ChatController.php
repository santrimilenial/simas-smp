<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\TeachingLog;
use App\Models\ClassModel;
use App\Models\Attendance;
use App\Models\User;

class ChatController extends Controller
{
    public function chat(Request $request)
    {
        // Rate limiting - max 30 requests per minute per user
        $key = 'chat:' . auth()->id();
        if (RateLimiter::tooManyAttempts($key, 30)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'error' => 'Terlalu banyak permintaan. Silakan tunggu ' . $seconds . ' detik.'
            ], 429);
        }
        RateLimiter::hit($key, 60);

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $userMessage = $request->message;
        $apiKey = config('services.gemini.api_key');

        if (!$apiKey) {
            return response()->json([
                'error' => 'API Key Gemini belum dikonfigurasi. Silakan tambahkan GEMINI_API_KEY di file .env'
            ], 500);
        }

        try {
            // Dapatkan konteks aplikasi
            $context = $this->getApplicationContext();
            
            // Buat prompt dengan konteks
            $systemPrompt = "Kamu adalah asisten AI untuk SIMAS (Sistem Informasi Sekolah). " .
                "Kamu memiliki DUA peran utama:\n" .
                "1. Membantu guru dan admin dalam mengelola sistem: jurnal mengajar, absensi, laporan, dll.\n" .
                "2. Membantu guru dengan materi pelajaran: menjawab pertanyaan tentang matematika, sains, bahasa, sejarah, dan mata pelajaran lainnya.\n\n" .
                "Berikan jawaban yang informatif, sopan, dan relevan. " .
                "Jika ditanya tentang data sistem SIMAS, gunakan informasi dari konteks di bawah. " .
                "Jika ditanya tentang materi pelajaran, gunakan pengetahuanmu sebagai AI yang berpengetahuan luas.\n\n" .
                "KONTEKS SISTEM SIMAS:\n" . $context . "\n\n" .
                "Pertanyaan User: " . $userMessage;

            // Panggil Gemini API
            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post("https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $systemPrompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'maxOutputTokens' => 2048,
                    ]
                ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    $aiResponse = $data['candidates'][0]['content']['parts'][0]['text'];
                    
                    // Cek apakah response terpotong
                    $finishReason = $data['candidates'][0]['finishReason'] ?? 'STOP';
                    if ($finishReason === 'MAX_TOKENS') {
                        $aiResponse .= "\n\n_[Catatan: Jawaban mungkin terpotong karena terlalu panjang. Silakan ajukan pertanyaan yang lebih spesifik]_";
                    }
                    
                    return response()->json([
                        'success' => true,
                        'message' => $aiResponse
                    ]);
                } else {
                    // Log response untuk debugging
                    \Log::error('Gemini API response tidak sesuai format', ['response' => $data]);
                    
                    return response()->json([
                        'error' => 'Format respons dari AI tidak sesuai. Silakan coba lagi.'
                    ], 500);
                }
            }

            // Log error response
            $errorBody = $response->body();
            $errorStatus = $response->status();
            \Log::error('Gemini API request failed', [
                'status' => $errorStatus,
                'body' => $errorBody
            ]);

            return response()->json([
                'error' => 'Gagal mendapatkan respons dari AI (HTTP ' . $errorStatus . '). Silakan periksa API key atau coba lagi.'
            ], 500);

        } catch (\Exception $e) {
            \Log::error('Chatbot error: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Terjadi kesalahan saat menghubungi AI. Silakan coba lagi nanti.'
            ], 500);
        }
    }

    private function getApplicationContext()
    {
        $user = auth()->user();
        $context = "";

        // Informasi user saat ini
        $context .= "User yang login:\n";
        $context .= "- Nama: {$user->name}\n";
        $context .= "- NIY: {$user->niy}\n";
        $context .= "- Role: " . ucfirst($user->role) . "\n\n";

        // Statistik jurnal mengajar (jika user adalah guru)
        if ($user->role === 'guru') {
            $totalJurnal = TeachingLog::where('user_id', $user->id)->count();
            $jurnalBulanIni = TeachingLog::where('user_id', $user->id)
                ->whereMonth('log_date', now()->month)
                ->whereYear('log_date', now()->year)
                ->count();
            
            $context .= "Statistik Jurnal Mengajar User:\n";
            $context .= "- Total jurnal: {$totalJurnal} jurnal\n";
            $context .= "- Jurnal bulan ini: {$jurnalBulanIni} jurnal\n\n";

            // Jurnal terakhir
            $lastJurnal = TeachingLog::where('user_id', $user->id)
                ->latest('log_date')
                ->first();
            
            if ($lastJurnal) {
                $context .= "Jurnal Terakhir:\n";
                $context .= "- Mata Pelajaran: {$lastJurnal->subject}\n";
                $context .= "- Kelas: {$lastJurnal->class}\n";
                $context .= "- Materi: {$lastJurnal->material}\n";
                $context .= "- Tanggal: {$lastJurnal->log_date->format('d/m/Y')}\n\n";
            }

            // Absensi hari ini
            $todayAttendance = Attendance::where('user_id', $user->id)
                ->whereDate('check_in_time', today())
                ->first();
            
            if ($todayAttendance) {
                $context .= "Absensi Hari Ini:\n";
                $context .= "- Waktu masuk: " . ($todayAttendance->check_in_time ? $todayAttendance->check_in_time->format('H:i') : '-') . "\n";
                $context .= "- Waktu pulang: " . ($todayAttendance->check_out_time ? $todayAttendance->check_out_time->format('H:i') : 'Belum absen pulang') . "\n\n";
            } else {
                $context .= "Absensi Hari Ini: Belum melakukan absensi\n\n";
            }
        }

        // Informasi umum aplikasi untuk admin
        if ($user->role === 'admin') {
            $totalGuru = User::where('role', 'guru')->count();
            $totalKelas = ClassModel::count();
            $totalJurnalSemua = TeachingLog::count();
            
            $context .= "Statistik Sistem:\n";
            $context .= "- Total Guru: {$totalGuru} orang\n";
            $context .= "- Total Kelas: {$totalKelas} kelas\n";
            $context .= "- Total Jurnal (semua guru): {$totalJurnalSemua} jurnal\n\n";
        }

        // Daftar kelas aktif
        $activeClasses = ClassModel::active()->ordered()->limit(10)->get();
        if ($activeClasses->count() > 0) {
            $context .= "Daftar Kelas Aktif:\n";
            foreach ($activeClasses as $class) {
                $context .= "- {$class->name} (Tingkat {$class->grade_level}, {$class->student_count} siswa)\n";
            }
            $context .= "\n";
        }

        // Informasi fitur aplikasi
        $context .= "Fitur Aplikasi:\n";
        $context .= "- Pencatatan jurnal mengajar (mata pelajaran, materi, kelas, dll)\n";
        $context .= "- Absensi guru (masuk dan pulang) dengan QR code\n";
        $context .= "- Manajemen kelas dan data guru\n";
        $context .= "- Laporan dan export ke PDF/Excel\n";
        $context .= "- Tujuan pembelajaran per mata pelajaran\n";

        return $context;
    }
}
