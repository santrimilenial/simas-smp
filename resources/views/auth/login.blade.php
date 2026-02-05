<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMAS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-blue-500 to-purple-600 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8">
        <div class="text-center mb-8">
            <div class="inline-block bg-blue-100 rounded-full p-4 mb-4">
                <i class="fas fa-graduation-cap text-4xl text-blue-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">SIMAS</h1>
            <p class="text-gray-600 mt-2">Silakan login untuk melanjutkan</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 mb-4 rounded text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 mb-4 rounded text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2" for="email">
                    Email
                </label>
                <input 
                    type="email" 
                    name="email" 
                    id="email"
                    value="{{ old('email') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="nama@email.com"
                    required
                >
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-semibold mb-2" for="password">
                    Password
                </label>
                <div class="relative">
                    <input 
                        type="password" 
                        name="password" 
                        id="password"
                        class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="••••••••"
                        required
                    >
                    <button 
                        type="button"
                        onclick="togglePassword()"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none"
                    >
                        <i id="toggleIcon" class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="flex items-center mb-6">
                <input 
                    type="checkbox" 
                    name="remember" 
                    id="remember"
                    class="mr-2 w-4 h-4 text-blue-600"
                >
                <label for="remember" class="text-sm text-gray-700">Ingat saya</label>
            </div>

            <button 
                type="submit"
                class="w-full bg-gradient-to-r from-blue-500 to-blue-700 text-white py-3 rounded-lg font-semibold hover:from-blue-600 hover:to-blue-800 transition duration-300"
            >
                Login
            </button>
        </form>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>