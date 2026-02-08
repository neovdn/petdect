<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Smart Waste Bank</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-white min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

    <div class="absolute top-0 left-0 w-96 h-96 bg-emerald-100 rounded-full mix-blend-multiply filter blur-3xl opacity-60 -translate-x-1/2 -translate-y-1/2 animate-blob"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-60 translate-x-1/2 translate-y-1/2 animate-blob animation-delay-2000"></div>

    <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] w-full max-w-md p-8 z-10 border border-slate-100 relative">
        
        <div class="text-center mb-8">
            <div class="h-20 w-20 bg-gradient-to-tr from-emerald-400 to-blue-500 rounded-2xl mx-auto flex items-center justify-center shadow-lg shadow-blue-200 mb-5 transform transition hover:scale-105 duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Smart Waste Bank</h2>
            <p class="text-sm text-slate-500 mt-2">Masuk untuk mulai mengelola lingkungan</p>
        </div>

        @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 flex items-start space-x-3">
            <svg class="h-5 w-5 text-red-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf
            
            <div>
                <label for="username" class="block text-sm font-medium text-slate-700 mb-2 pl-1">Username</label>
                <div class="relative">
                    <input type="text" 
                           id="username" 
                           name="username" 
                           value="{{ old('username') }}"
                           required
                           class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-100 transition-all duration-200 text-sm"
                           placeholder="Masukkan username Anda">
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-2 pl-1">
                    <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                </div>
                <div class="relative">
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required
                           class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-100 transition-all duration-200 text-sm"
                           placeholder="••••••••">
                </div>
            </div>

            <button type="submit" 
                    class="w-full py-3.5 px-4 bg-gradient-to-r from-emerald-400 to-blue-500 hover:from-emerald-500 hover:to-blue-600 text-white font-semibold rounded-xl shadow-lg shadow-blue-200 transform transition-all duration-200 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mt-2">
                Sign In
            </button>

        </form>
        
        <div class="mt-8 text-center">
            <p class="text-xs text-slate-400">
                &copy; {{ date('Y') }} Smart Waste Bank System
            </p>
        </div>
    </div>

</body>
</html>