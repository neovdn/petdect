<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Smart Waste Bank')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Custom colors untuk tema Nature */
        :root {
            --primary-green: #2D5016;
            --secondary-green: #4A7C2C;
            --light-green: #7FB069;
            --cream: #FFFBF5;
            --wood-brown: #8B4513;
            --text-dark: #2C3E1F;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-[#FFFBF5] text-[#2C3E1F]">
    
    <!-- Navbar -->
    @auth
    <nav class="bg-[#2D5016] text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <!-- Logo -->
                        <svg class="h-8 w-8 text-[#7FB069]" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                        </svg>
                        <span class="ml-2 text-xl font-bold">Smart Waste Bank</span>
                    </div>
                    
                    <!-- Navigation Links -->
                    <div class="hidden md:ml-10 md:flex md:space-x-4">
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" 
                               class="px-3 py-2 rounded-md text-sm font-medium hover:bg-[#4A7C2C] transition {{ request()->routeIs('admin.dashboard') ? 'bg-[#4A7C2C]' : '' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('admin.waste-prices') }}" 
                               class="px-3 py-2 rounded-md text-sm font-medium hover:bg-[#4A7C2C] transition {{ request()->routeIs('admin.waste-prices') ? 'bg-[#4A7C2C]' : '' }}">
                                Harga Sampah
                            </a>
                            <a href="{{ route('admin.customers') }}" 
                               class="px-3 py-2 rounded-md text-sm font-medium hover:bg-[#4A7C2C] transition {{ request()->routeIs('admin.customers') ? 'bg-[#4A7C2C]' : '' }}">
                                Customer
                            </a>
                            <a href="{{ route('admin.users') }}" 
                               class="px-3 py-2 rounded-md text-sm font-medium hover:bg-[#4A7C2C] transition {{ request()->routeIs('admin.users') ? 'bg-[#4A7C2C]' : '' }}">
                                Users
                            </a>
                        @endif
                        
                        <a href="{{ route('cashier.index') }}" 
                           class="px-3 py-2 rounded-md text-sm font-medium hover:bg-[#4A7C2C] transition {{ request()->routeIs('cashier.index') ? 'bg-[#4A7C2C]' : '' }}">
                            Kasir
                        </a>
                        <a href="{{ route('cashier.history') }}" 
                           class="px-3 py-2 rounded-md text-sm font-medium hover:bg-[#4A7C2C] transition {{ request()->routeIs('cashier.history') ? 'bg-[#4A7C2C]' : '' }}">
                            Riwayat
                        </a>
                    </div>
                </div>
                
                <!-- User Info & Logout -->
                <div class="flex items-center space-x-4">
                    <div class="text-sm">
                        <div class="font-semibold">{{ auth()->user()->full_name }}</div>
                        <div class="text-xs text-[#7FB069]">{{ ucfirst(auth()->user()->role) }}</div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="bg-[#8B4513] hover:bg-[#A0522D] px-4 py-2 rounded-lg text-sm font-medium transition">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    @endauth
    
    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="bg-[#2D5016] text-white py-6 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-sm">&copy; {{ date('Y') }} Smart Waste Bank - Tugas Akhir IoT</p>
        </div>
    </footer>
    
    @stack('scripts')
</body>
</html>