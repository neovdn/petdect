<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Smart Waste Bank</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-[#7FB069] to-[#2D5016] min-h-screen flex items-center justify-center">
    
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8">
        <!-- Logo & Title -->
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <svg class="h-16 w-16 text-[#2D5016]" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-[#2D5016] mb-2">Smart Waste Bank</h1>
            <p class="text-gray-600 text-sm">Sistem Kasir Bank Sampah Berbasis IoT</p>
        </div>
        
        <!-- Error Messages -->
        @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        
        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="mb-4">
                <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">
                    Username
                </label>
                <input type="text" 
                       id="username" 
                       name="username" 
                       value="{{ old('username') }}"
                       required
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-[#2D5016] transition"
                       placeholder="Masukkan username">
            </div>
            
            <div class="mb-6">
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                    Password
                </label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       required
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-[#2D5016] transition"
                       placeholder="Masukkan password">
            </div>
            
            <button type="submit" 
                    class="w-full bg-[#2D5016] hover:bg-[#4A7C2C] text-white font-bold py-3 rounded-lg transition duration-200 transform hover:scale-105">
                Login
            </button>
        </form>
        
        <!-- Demo Credentials -->
        <div class="mt-6 p-4 bg-[#FFFBF5] rounded-lg border-2 border-[#7FB069]">
            <p class="text-xs font-semibold text-gray-700 mb-2">Demo Credentials:</p>
            <div class="text-xs text-gray-600 space-y-1">
                <p><strong>Admin:</strong> admin / admin123</p>
                <p><strong>Kasir:</strong> kasir / kasir123</p>
            </div>
        </div>
    </div>
    
</body>
</html>