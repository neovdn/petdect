<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Smart Waste Bank</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Poppins', sans-serif; }
        .sidebar-transition { transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        /* Hide scrollbar for Chrome, Safari and Opera */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        /* Hide scrollbar for IE, Edge and Firefox */
        .no-scrollbar { -ms-overflow-style: none;  scrollbar-width: none; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

    <div class="flex h-screen overflow-hidden">

        <aside class="sidebar-transition group w-20 hover:w-72 bg-white border-r border-slate-200 flex flex-col fixed h-full z-30 shadow-sm">
            
            <div class="h-16 flex items-center justify-center border-b border-slate-100 group-hover:justify-start group-hover:px-6 transition-all">
                <div class="h-10 w-10 bg-gradient-to-tr from-emerald-400 to-blue-500 rounded-xl flex items-center justify-center text-white shrink-0 shadow-md shadow-blue-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <span class="ml-3 font-bold text-lg text-slate-800 opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap overflow-hidden delay-100">
                    SmartWaste
                </span>
            </div>

            <nav class="flex-1 py-6 space-y-2 overflow-y-auto px-3 no-scrollbar">
                
                @php
                    function menuItem($route, $icon, $label, $isActive = false) {
                        // Jika route adalah '#', jangan error
                        $href = ($route !== '#') ? route($route) : '#';
                        $activeClass = $isActive ? 'bg-blue-50 text-blue-600' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900';
                        
                        return '
                        <a href="'.$href.'" class="flex items-center px-3 py-3 rounded-xl transition-all duration-200 group/item '.$activeClass.'">
                            <div class="shrink-0">
                                '.$icon.'
                            </div>
                            <span class="ml-3 font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap overflow-hidden delay-75">
                                '.$label.'
                            </span>
                        </a>
                        ';
                    }
                @endphp

                {!! menuItem(
                    'admin.dashboard', 
                    '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>', 
                    'Dashboard', 
                    request()->routeIs('admin.dashboard')
                ) !!}

                {!! menuItem(
                    'cashier.index', 
                    '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>', 
                    'Kasir & Transaksi',
                    request()->routeIs('cashier.*')
                ) !!}

                {!! menuItem(
                    'admin.waste-prices', 
                    '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>', 
                    'Master Data',
                    request()->routeIs('admin.waste-prices') || request()->routeIs('admin.customers') || request()->routeIs('admin.users')
                ) !!}

            </nav>

            <div class="p-4 border-t border-slate-100">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-3 py-3 rounded-xl text-red-500 hover:bg-red-50 transition-all duration-200 group/logout">
                        <div class="shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </div>
                        <span class="ml-3 font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap overflow-hidden delay-75">
                            Sign Out
                        </span>
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col h-screen pl-20 transition-all duration-300 w-full relative">
            
            <header class="h-16 bg-white/80 backdrop-blur-md border-b border-slate-200 flex items-center justify-between px-8 sticky top-0 z-20">
                <div>
                    <h1 class="text-xl font-bold text-slate-800 tracking-tight">@yield('header_title', 'Dashboard')</h1>
                    <p class="text-xs text-slate-400">Welcome back, {{ Auth::user()->name ?? 'Administrator' }}</p>
                </div>

                <div class="flex items-center space-x-4">
                     <button class="relative p-2 text-slate-400 hover:text-blue-500 transition-colors">
                        <span class="absolute top-2 right-2 h-2 w-2 bg-red-500 rounded-full"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </button>

                    <div class="flex items-center space-x-3 pl-4 border-l border-slate-200">
                        <div class="text-right hidden md:block">
                            <div class="text-sm font-semibold text-slate-700">{{ Auth::user()->name ?? 'User' }}</div>
                            <div class="text-xs text-slate-400">{{ Auth::user()->role ?? 'Admin' }}</div>
                        </div>
                        <div class="h-10 w-10 rounded-full bg-slate-200 overflow-hidden border-2 border-white shadow-sm">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'A') }}&background=0D9488&color=fff" alt="Avatar">
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-8">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>