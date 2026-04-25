<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #020617; color: white; }
        .glass { background: rgba(255, 255, 255, 0.02); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.05); }
    </style>
</head>
<body class="min-h-screen">
    
    <!-- Navbar -->
    <nav class="h-20 glass sticky top-0 z-50 flex items-center justify-between px-8 border-x-0 border-t-0">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center font-bold">SA</div>
            <h1 class="text-xl font-bold tracking-tight">Super Admin <span class="text-indigo-500">Panel</span></h1>
        </div>
        <div class="flex items-center gap-6">
            <div class="text-right">
                <p class="text-sm font-medium">Platform Master</p>
                <p class="text-xs text-gray-500">All access granted</p>
            </div>
            <form action="{{ route('superadmin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-500/10 hover:bg-red-500/20 text-red-500 px-4 py-2 rounded-lg text-sm transition-all font-medium">Logout</button>
            </form>
        </div>
    </nav>

    <main class="p-8 space-y-8 max-w-7xl mx-auto">
        
        <div class="flex justify-between items-end">
            <div>
                <h2 class="text-3xl font-bold">Library Network</h2>
                <p class="text-gray-400">Manage all library owners and their subscription status.</p>
            </div>
            <div class="bg-indigo-600/20 border border-indigo-500/30 px-6 py-3 rounded-2xl">
                <span class="text-gray-400 text-sm">Total Libraries:</span>
                <span class="text-2xl font-bold ml-2">{{ $libraries->count() }}</span>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 p-4 rounded-2xl">
                {{ session('success') }}
            </div>
        @endif

        <!-- Library List Table -->
        <div class="glass rounded-3xl overflow-hidden shadow-2xl">
            <table class="w-full text-left">
                <thead class="bg-white/5 border-b border-white/10">
                    <tr>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-400">Library & Owner</th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-400">Address</th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-400 text-center">Seats</th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-400">Usage Duration</th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-400">Auth Info</th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-400 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($libraries as $lib)
                    <tr class="hover:bg-white/5 transition-all group">
                        <td class="px-6 py-6">
                            <p class="font-bold text-lg group-hover:text-indigo-400 transition-colors">{{ $lib->library_name }}</p>
                            <p class="text-sm text-gray-400">{{ $lib->owner_name }}</p>
                        </td>
                        <td class="px-6 py-6 text-sm text-gray-400 max-w-xs truncate">{{ $lib->address }}</td>
                        <td class="px-6 py-6 text-center">
                            <span class="bg-indigo-500/10 text-indigo-400 px-3 py-1 rounded-full text-xs font-bold border border-indigo-500/20">
                                {{ $lib->total_seats }} Max
                            </span>
                        </td>
                        <td class="px-6 py-6">
                            <p class="text-sm text-white font-medium">{{ $lib->usage_duration }}</p>
                            <p class="text-[10px] text-gray-500 uppercase">Since {{ $lib->created_at->format('M d, Y') }}</p>
                        </td>
                        <td class="px-6 py-6">
                            <p class="text-sm text-white">{{ $lib->email }}</p>
                            @if($lib->mobile_number)
                                <p class="text-xs text-indigo-300 font-mono mt-1">📞 {{ $lib->mobile_number }}</p>
                            @endif
                            <p class="text-xs text-emerald-500 font-mono mt-1">Password: 
                                @php
                                    try {
                                        $decryptedPassword = \Illuminate\Support\Facades\Crypt::decryptString($lib->password);
                                    } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                                        $decryptedPassword = 'Hash (Update Required)';
                                    }
                                @endphp
                                {{ $decryptedPassword }}
                            </p>
                        </td>
                        <td class="px-6 py-6 text-right space-x-2">
                            <a href="{{ route('superadmin.library.edit', $lib->id) }}" class="inline-block bg-white/10 hover:bg-indigo-600 p-2 rounded-lg transition-all" title="Edit Library">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form action="{{ route('superadmin.library.delete', $lib->id) }}" method="POST" class="inline-block">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this library?')" class="bg-white/10 hover:bg-red-600 p-2 rounded-lg transition-all" title="Delete Library">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </main>

</body>
</html>
