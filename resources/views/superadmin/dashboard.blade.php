<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #020617; color: white; }
        .glass { background: rgba(255, 255, 255, 0.02); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.05); }
    </style>
</head>
<body class="min-h-screen">
    
    <!-- Navbar -->
    <nav class="h-16 md:h-20 glass sticky top-0 z-50 flex items-center justify-between px-4 md:px-8 border-x-0 border-t-0">
        <div class="flex items-center gap-2 md:gap-3">
            <div class="w-8 h-8 md:w-10 md:h-10 bg-indigo-600 rounded-xl flex items-center justify-center font-bold text-sm md:text-base">SA</div>
            <h1 class="text-base md:text-xl font-bold tracking-tight">Super Admin <span class="text-indigo-500">Panel</span></h1>
        </div>
        <div class="flex items-center gap-3 md:gap-6">
            <div class="text-right hidden sm:block">
                <p class="text-sm font-medium">Platform Master</p>
                <p class="text-xs text-gray-500">All access granted</p>
            </div>
            <form action="{{ route('superadmin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-500/10 hover:bg-red-500/20 text-red-500 px-3 md:px-4 py-2 rounded-lg text-xs md:text-sm transition-all font-medium">Logout</button>
            </form>
        </div>
    </nav>

    <main class="p-4 md:p-8 space-y-6 md:space-y-8 max-w-7xl mx-auto">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold">Library Network</h2>
                <p class="text-gray-400 text-sm">Manage all library owners and their subscription status.</p>
            </div>
            <div class="bg-indigo-600/20 border border-indigo-500/30 px-4 md:px-6 py-2 md:py-3 rounded-xl md:rounded-2xl">
                <span class="text-gray-400 text-sm">Total Libraries:</span>
                <span class="text-xl md:text-2xl font-bold ml-2">{{ $libraries->count() }}</span>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 p-4 rounded-xl md:rounded-2xl text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Library List -->
        <div class="glass rounded-xl md:rounded-3xl overflow-hidden shadow-2xl">
            
            <!-- Desktop Table (hidden on mobile) -->
            <div class="hidden lg:block">
                <table class="w-full text-left">
                    <thead class="bg-white/5 border-b border-white/10">
                        <tr>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-400">Library & Owner</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-400">Address</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-400 text-center">Seats</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-400">Usage Duration</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-400">Auth Info</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-400 text-center">Status</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-400 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($libraries as $lib)
                        <tr class="hover:bg-white/5 transition-all group" x-data="{ openMsg: false }">
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
                                <div class="space-y-1">
                                    <p class="text-sm text-white">{{ $lib->email }}</p>
                                    @if($lib->mobile_number)
                                        <p class="text-xs text-indigo-300 font-mono">📞 {{ $lib->mobile_number }}</p>
                                    @endif
                                    <p class="text-xs text-emerald-500 font-mono">Password: 
                                        @php
                                            try {
                                                $decryptedPassword = \Illuminate\Support\Facades\Crypt::decryptString($lib->password);
                                            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                                                $decryptedPassword = 'Hash (Update Required)';
                                            }
                                        @endphp
                                        {{ $decryptedPassword }}
                                    </p>
                                    @if($lib->alert_message)
                                        <div class="mt-2 text-[10px] bg-amber-500/10 text-amber-400 p-2 rounded border border-amber-500/20">
                                            <strong>Current Message:</strong> {{ $lib->alert_message }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-6 text-center">
                                @if($lib->is_active)
                                    <span class="bg-emerald-500/10 text-emerald-500 px-3 py-1 rounded-full text-[10px] font-bold uppercase border border-emerald-500/20">Active</span>
                                @else
                                    <span class="bg-red-500/10 text-red-500 px-3 py-1 rounded-full text-[10px] font-bold uppercase border border-red-500/20">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="openMsg = !openMsg" class="p-2 rounded-lg bg-indigo-500/10 hover:bg-indigo-600 text-indigo-500 transition-all" title="Send Message">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                                    </button>
                                    <form action="{{ route('superadmin.library.toggle', $lib->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="p-2 rounded-lg transition-all {{ $lib->is_active ? 'bg-amber-500/10 hover:bg-amber-600 text-amber-500' : 'bg-emerald-500/10 hover:bg-emerald-600 text-emerald-500' }}" title="{{ $lib->is_active ? 'Deactivate' : 'Activate' }} Library">
                                            @if($lib->is_active)
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                            @else
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            @endif
                                        </button>
                                    </form>
                                    <a href="{{ route('superadmin.library.edit', $lib->id) }}" class="inline-block bg-white/10 hover:bg-indigo-600 p-2 rounded-lg transition-all" title="Edit Library">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('superadmin.library.delete', $lib->id) }}" method="POST" class="inline-block">
                                        @csrf @method('DELETE')
                                        <button type="submit" onclick="return confirm('Are you sure you want to delete this library?')" class="bg-white/10 hover:bg-red-600 p-2 rounded-lg transition-all" title="Delete Library">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                                
                                <!-- Message Input Box -->
                                <div x-show="openMsg" x-transition class="mt-4 p-4 glass rounded-2xl text-left border-indigo-500/30">
                                    <form action="{{ route('superadmin.library.message', $lib->id) }}" method="POST" class="space-y-3">
                                        @csrf
                                        <label class="text-xs text-gray-400 uppercase">System Alert Message</label>
                                        <textarea name="alert_message" rows="2" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm focus:border-indigo-500 outline-none" placeholder="Enter message for owner...">{{ $lib->alert_message }}</textarea>
                                        <div class="flex gap-2">
                                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 px-4 py-1.5 rounded-lg text-xs font-bold transition-all">Save Message</button>
                                            @if($lib->alert_message)
                                                <button type="submit" name="alert_message" value="" class="bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white px-4 py-1.5 rounded-lg text-xs font-bold transition-all border border-red-500/20">Clear</button>
                                            @endif
                                            <button type="button" @click="openMsg = false" class="bg-white/5 hover:bg-white/10 px-4 py-1.5 rounded-lg text-xs transition-all">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile/Tablet Cards (hidden on desktop) -->
            <div class="lg:hidden divide-y divide-white/5">
                @foreach($libraries as $lib)
                <div class="p-4 hover:bg-white/5 transition" x-data="{ openMsg: false, showDetails: false }">
                    <!-- Card Header -->
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-indigo-600/20 border border-indigo-500/30 flex items-center justify-center text-indigo-400 font-bold text-sm shrink-0">
                                {{ substr($lib->library_name, 0, 1) }}
                            </div>
                            <div class="min-w-0">
                                <p class="font-bold text-white text-sm truncate">{{ $lib->library_name }}</p>
                                <p class="text-[11px] text-gray-500 truncate">{{ $lib->owner_name }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            @if($lib->is_active)
                                <span class="bg-emerald-500/10 text-emerald-500 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase border border-emerald-500/20">Active</span>
                            @else
                                <span class="bg-red-500/10 text-red-500 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase border border-red-500/20">Inactive</span>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Info -->
                    <div class="grid grid-cols-2 gap-2 text-xs mb-3">
                        <div class="bg-white/5 rounded-lg p-2.5">
                            <p class="text-gray-500 mb-0.5">💺 Seats</p>
                            <p class="text-indigo-400 font-bold">{{ $lib->total_seats }} Max</p>
                        </div>
                        <div class="bg-white/5 rounded-lg p-2.5">
                            <p class="text-gray-500 mb-0.5">⏱ Usage</p>
                            <p class="text-gray-300">{{ $lib->usage_duration }}</p>
                        </div>
                        <div class="bg-white/5 rounded-lg p-2.5 col-span-2">
                            <p class="text-gray-500 mb-0.5">📍 Address</p>
                            <p class="text-gray-300 truncate">{{ $lib->address }}</p>
                        </div>
                    </div>

                    <!-- Toggle Details Button -->
                    <button @click="showDetails = !showDetails" class="w-full text-center text-xs text-indigo-400 bg-indigo-500/10 py-2 rounded-lg mb-3 hover:bg-indigo-500/20 transition">
                        <span x-show="!showDetails">📋 Show Auth Details</span>
                        <span x-show="showDetails">📋 Hide Auth Details</span>
                    </button>

                    <!-- Auth Details (Expandable) -->
                    <div x-show="showDetails" x-transition class="bg-white/5 rounded-lg p-3 mb-3 text-xs space-y-1.5">
                        <div class="flex items-center gap-2">
                            <span class="text-gray-500">📧</span>
                            <span class="text-white break-all">{{ $lib->email }}</span>
                        </div>
                        @if($lib->mobile_number)
                        <div class="flex items-center gap-2">
                            <span class="text-gray-500">📞</span>
                            <span class="text-indigo-300 font-mono">{{ $lib->mobile_number }}</span>
                        </div>
                        @endif
                        <div class="flex items-center gap-2">
                            <span class="text-gray-500">🔑</span>
                            <span class="text-emerald-500 font-mono break-all">
                                @php
                                    try {
                                        $decryptedPassword = \Illuminate\Support\Facades\Crypt::decryptString($lib->password);
                                    } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                                        $decryptedPassword = 'Hash (Update Required)';
                                    }
                                @endphp
                                {{ $decryptedPassword }}
                            </span>
                        </div>
                        <div class="text-[10px] text-gray-500">
                            Since {{ $lib->created_at->format('M d, Y') }}
                        </div>
                        @if($lib->alert_message)
                            <div class="mt-2 text-[10px] bg-amber-500/10 text-amber-400 p-2 rounded border border-amber-500/20">
                                <strong>Current Message:</strong> {{ $lib->alert_message }}
                            </div>
                        @endif
                    </div>

                    <!-- Actions Row -->
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <button @click="openMsg = !openMsg" class="p-2 rounded-lg bg-indigo-500/10 hover:bg-indigo-600 text-indigo-500 transition-all" title="Send Message">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                            </button>
                            <form action="{{ route('superadmin.library.toggle', $lib->id) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" class="p-2 rounded-lg transition-all {{ $lib->is_active ? 'bg-amber-500/10 hover:bg-amber-600 text-amber-500' : 'bg-emerald-500/10 hover:bg-emerald-600 text-emerald-500' }}" title="{{ $lib->is_active ? 'Deactivate' : 'Activate' }}">
                                    @if($lib->is_active)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @endif
                                </button>
                            </form>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('superadmin.library.edit', $lib->id) }}" class="inline-block bg-white/10 hover:bg-indigo-600 p-2 rounded-lg transition-all" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form action="{{ route('superadmin.library.delete', $lib->id) }}" method="POST" class="inline-block">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this library?')" class="bg-white/10 hover:bg-red-600 p-2 rounded-lg transition-all" title="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Message Input Box (Mobile) -->
                    <div x-show="openMsg" x-transition class="mt-3 p-3 glass rounded-xl text-left border-indigo-500/30">
                        <form action="{{ route('superadmin.library.message', $lib->id) }}" method="POST" class="space-y-3">
                            @csrf
                            <label class="text-xs text-gray-400 uppercase">System Alert Message</label>
                            <textarea name="alert_message" rows="2" class="w-full bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 outline-none" placeholder="Enter message for owner...">{{ $lib->alert_message }}</textarea>
                            <div class="flex flex-wrap gap-2">
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 px-3 py-1.5 rounded-lg text-xs font-bold transition-all">Save</button>
                                @if($lib->alert_message)
                                    <button type="submit" name="alert_message" value="" class="bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-all border border-red-500/20">Clear</button>
                                @endif
                                <button type="button" @click="openMsg = false" class="bg-white/5 hover:bg-white/10 px-3 py-1.5 rounded-lg text-xs transition-all">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </main>

</body>
</html>
