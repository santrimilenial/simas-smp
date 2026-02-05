<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-full mb-4">
                    <i class="fas fa-envelope-open-text text-3xl text-white"></i>
                </div>
                <h1 class="text-2xl font-bold text-white">Verifikasi Email</h1>
                <p class="text-blue-100 mt-2">Satu langkah lagi untuk mengakses akun Anda</p>
            </div>
            
            <!-- Content -->
            <div class="px-8 py-6">
                <div class="text-center mb-6">
                    <p class="text-gray-600 leading-relaxed">
                        Terima kasih telah mendaftar! Sebelum memulai, silakan verifikasi email Anda dengan mengklik tautan yang kami kirimkan ke email Anda.
                    </p>
                </div>
                
                @if (session('status') == 'verification-link-sent')
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-0.5 mr-3"></i>
                        <p class="text-green-700 text-sm">
                            Link verifikasi baru telah dikirim ke alamat email Anda.
                        </p>
                    </div>
                @endif
                
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
                        <div class="text-sm text-blue-700">
                            <p class="font-medium">Email dikirim ke:</p>
                            <p class="mt-1 font-mono">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 px-4 rounded-lg font-semibold hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 flex items-center justify-center">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Kirim Ulang Email Verifikasi
                        </button>
                    </form>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full bg-gray-100 text-gray-700 py-3 px-4 rounded-lg font-semibold hover:bg-gray-200 transition-all duration-200 flex items-center justify-center">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="bg-gray-50 px-8 py-4 border-t">
                <p class="text-center text-sm text-gray-500">
                    Tidak menerima email? Periksa folder spam Anda atau coba kirim ulang.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
