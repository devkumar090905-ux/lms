@extends('layouts.admin')

@section('title', 'Manage Seats')
@section('page_title', 'Manage Library Seats')

@section('content')
<div class="p-8 space-y-8 flex-1">
    
    <!-- Add Seats Section -->
    <div class="glass-panel p-6 rounded-2xl">
        <h3 class="text-xl font-semibold text-white mb-4">Bulk Add Seats</h3>
        <p class="text-sm text-gray-400 mb-6">Quickly generate a range of seats. Leave Prefix blank if not needed.</p>
        
        <form action="{{ route('seats.store') }}" method="POST" class="flex flex-col md:flex-row items-end gap-4">
            @csrf
            
            <div class="flex flex-col w-full md:w-1/4">
                <label for="prefix" class="text-sm text-gray-400 mb-1">Prefix (e.g. 'A')</label>
                <input type="text" name="prefix" id="prefix" class="bg-black/50 border border-gray-700 rounded-lg px-4 py-2 text-white placeholder-gray-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" placeholder="A" value="{{ old('prefix') }}">
                @error('prefix') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            
            <div class="flex flex-col w-full md:w-1/4">
                <label for="start_number" class="text-sm text-gray-400 mb-1">Start Number</label>
                <input type="number" name="start_number" id="start_number" class="bg-black/50 border border-gray-700 rounded-lg px-4 py-2 text-white placeholder-gray-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" placeholder="1" value="{{ old('start_number') }}" required>
                @error('start_number') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="flex flex-col w-full md:w-1/4">
                <label for="end_number" class="text-sm text-gray-400 mb-1">End Number</label>
                <input type="number" name="end_number" id="end_number" class="bg-black/50 border border-gray-700 rounded-lg px-4 py-2 text-white placeholder-gray-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" placeholder="20" value="{{ old('end_number') }}" required>
                @error('end_number') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="w-full md:w-1/4">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-medium px-4 py-2 rounded-lg shadow-lg shadow-blue-500/20 transition-all border border-blue-500">
                    Generate Seats
                </button>
            </div>
        </form>
    </div>

    <!-- Live Seat Grid -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold text-white">Visual Seat Grid</h3>
            <div class="flex space-x-4 text-sm">
                <div class="flex items-center"><span class="w-3 h-3 rounded-full bg-emerald-500 mr-2 shadow-[0_0_10px_rgba(16,185,129,0.5)]"></span> <span class="text-gray-400">Available</span></div>
                <div class="flex items-center"><span class="w-3 h-3 rounded-full bg-rose-500 mr-2 shadow-[0_0_10px_rgba(244,63,94,0.5)]"></span> <span class="text-gray-400">Occupied</span></div>
                <div class="flex items-center"><span class="w-3 h-3 rounded-full bg-amber-500 mr-2 shadow-[0_0_10px_rgba(245,158,11,0.5)]"></span> <span class="text-gray-400">Maintenance</span></div>
            </div>
        </div>

        @if($seats->count() > 0)
            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-4 p-6 glass-panel rounded-2xl bg-black/40">
                @foreach($seats as $seat)
                    @php
                        $colorClass = match($seat->status) {
                            'available' => 'bg-emerald-500/10 border-emerald-500/50 text-emerald-400 hover:bg-emerald-500/20 shadow-[inset_0_0_15px_rgba(16,185,129,0.1)]',
                            'occupied' => 'bg-rose-500/10 border-rose-500/50 text-rose-400 hover:bg-rose-500/20 shadow-[inset_0_0_15px_rgba(244,63,94,0.1)]',
                            'maintenance' => 'bg-amber-500/10 border-amber-500/50 text-amber-400 hover:bg-amber-500/20 shadow-[inset_0_0_15px_rgba(245,158,11,0.1)]',
                            default => 'bg-gray-500/10 border-gray-500/50 text-gray-400',
                        };
                    @endphp
                    
                    <div class="relative group cursor-pointer aspect-square rounded-xl border-2 flex flex-col items-center justify-center transition-all duration-300 transform hover:scale-105 {{ $colorClass }}">
                        
                        <svg class="w-8 h-8 mb-1 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <span class="font-bold text-lg">{{ $seat->seat_number }}</span>
                        
                        <!-- Hover Menu (for updating status or deleting) -->
                        <div class="absolute inset-0 bg-black/90 backdrop-blur-sm rounded-xl flex-col items-center justify-center p-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200 hidden group-hover:flex z-10 border border-gray-700">
                            
                            <form action="{{ route('seats.update', $seat) }}" method="POST" class="w-full">
                                @csrf @method('PUT')
                                <select name="status" onchange="this.form.submit()" class="w-full text-xs bg-gray-800 text-white rounded border border-gray-600 mb-2 focus:outline-none p-1">
                                    <option value="available" {{ $seat->status == 'available' ? 'selected' : '' }}>Available</option>
                                    <option value="occupied" {{ $seat->status == 'occupied' ? 'selected' : '' }}>Occupied</option>
                                    <option value="maintenance" {{ $seat->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                </select>
                            </form>

                            <form action="{{ route('seats.destroy', $seat) }}" method="POST" class="w-full text-center">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs w-full py-1 rounded bg-red-500/20 text-red-500 hover:bg-red-500 hover:text-white transition">Delete</button>
                            </form>
                        </div>

                    </div>
                @endforeach
            </div>
        @else
            <div class="glass-panel p-12 rounded-2xl flex flex-col items-center justify-center text-center">
                <div class="w-20 h-20 rounded-full bg-gray-800 flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <h4 class="text-xl font-medium text-white mb-2">No seats found</h4>
                <p class="text-gray-400 max-w-md">Your library is currently empty. Use the bulk add form above to generate seats and start building out your visual map.</p>
            </div>
        @endif
        
    </div>

</div>
@endsection
