<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - LMS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #0f1115; color: #f3f4f6; }
        .glass-panel { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.05); }
        .gradient-text { background: linear-gradient(to right, #60a5fa, #a78bfa); -webkit-background-clip: text; color: transparent; }
    </style>
</head>
<body class="flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 glass-panel flex flex-col h-full shrink-0">
        <div class="h-16 flex items-center px-6 border-b border-gray-800">
            <h1 class="text-xl font-bold gradient-text tracking-wide space-x-1">
                <span>📚</span> <span>EasyLibrary</span>
            </h1>
        </div>
        <nav class="flex-1 py-6 px-4 space-y-2">
            <a href="{{ url('/') }}" class="flex items-center px-4 py-3 {{ request()->is('/') ? 'bg-blue-500/10 text-blue-400' : 'text-gray-400 hover:text-white hover:bg-white/5' }} rounded-xl transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Dashboard
            </a>
            <a href="{{ route('seats.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('seats.*') ? 'bg-blue-500/10 text-blue-400' : 'text-gray-400 hover:text-white hover:bg-white/5' }} rounded-xl transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                Manage Seats
            </a>
            <a href="{{ route('students.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('students.*') ? 'bg-blue-500/10 text-blue-400' : 'text-gray-400 hover:text-white hover:bg-white/5' }} rounded-xl transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Students
            </a>
            <a href="{{ route('bookings.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('bookings.*') ? 'bg-blue-500/10 text-blue-400' : 'text-gray-400 hover:text-white hover:bg-white/5' }} rounded-xl transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                Bookings
            </a>
        </nav>
        <div class="p-4">
            <div class="px-4 py-3 rounded-xl bg-gradient-to-r from-blue-600/20 to-purple-600/20 border border-blue-500/20 text-sm">
                <div class="flex items-center mb-2">
                    <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center mr-3 text-white font-bold">A</div>
                    <div>
                        <p class="text-white font-medium">Admin User</p>
                        <p class="text-xs text-gray-400">Owner</p>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-full bg-[#0a0a0c] overflow-y-auto">
        <!-- Header -->
        <header class="h-16 flex items-center justify-between px-8 glass-panel sticky top-0 z-10 p-4 shrink-0 border-x-0 border-t-0">
            <h2 class="text-lg font-medium text-white">@yield('page_title', 'Platform Overview')</h2>
            <div class="flex items-center space-x-4">
                <button class="text-gray-400 hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </button>
            </div>
        </header>

        <!-- Dynamic Content -->
        @if(session('success'))
        <div class="mx-8 mt-4 p-4 rounded-xl bg-emerald-500/20 border border-emerald-500/30 text-emerald-400">
            {{ session('success') }}
        </div>
        @endif
        
        @if(session('error'))
        <div class="mx-8 mt-4 p-4 rounded-xl bg-red-500/20 border border-red-500/30 text-red-400">
            {{ session('error') }}
        </div>
        @endif

        @yield('content')
        
    </main>

</body>
</html>
