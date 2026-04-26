@extends('layouts.admin')

@section('title', 'Manage Seats')
@section('page_title', 'Manage Library Seats')

@section('content')
<div class="p-4 md:p-8 space-y-6 md:space-y-8 flex-1">
    
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
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6 gap-4">
            <h3 class="text-xl font-semibold text-white">Visual Seat Grid</h3>
            <div class="flex flex-wrap gap-4 text-xs">
                <div class="flex items-center"><span class="w-3 h-3 rounded-full bg-emerald-500 mr-2 shadow-[0_0_8px_rgba(16,185,129,0.4)]"></span> <span class="text-gray-400">Available</span></div>
                <div class="flex items-center"><span class="w-3 h-3 rounded-full bg-blue-500 mr-2 shadow-[0_0_8px_rgba(59,130,246,0.4)]"></span> <span class="text-gray-400">1st Shift</span></div>
                <div class="flex items-center"><span class="w-3 h-3 rounded-full bg-indigo-500 mr-2 shadow-[0_0_8px_rgba(99,102,241,0.4)]"></span> <span class="text-gray-400">2nd Shift</span></div>
                <div class="flex items-center"><span class="w-3 h-3 rounded-full bg-rose-500 mr-2 shadow-[0_0_8px_rgba(244,63,94,0.4)]"></span> <span class="text-gray-400">Full Booked</span></div>
                <div class="flex items-center"><span class="w-3 h-3 rounded-full bg-amber-500 mr-2 shadow-[0_0_8px_rgba(245,158,11,0.4)]"></span> <span class="text-gray-400">Maintenance</span></div>
            </div>
        </div>

        @if($seats->count() > 0)
            <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 lg:grid-cols-10 gap-3 p-6 glass-panel rounded-2xl bg-black/40">
                @foreach($seats as $seat)
                    @php
                        $activeBookings = $seat->bookings;
                        $isFullTime = $activeBookings->where('shift_type', 'Full-time')->isNotEmpty();
                        $isFirstShift = $activeBookings->where('shift_type', 'First')->isNotEmpty();
                        $isSecondShift = $activeBookings->where('shift_type', 'Second')->isNotEmpty();
                        
                        $statusColor = 'available';
                        if ($seat->status === 'maintenance') {
                            $statusColor = 'maintenance';
                        } elseif ($isFullTime || ($isFirstShift && $isSecondShift)) {
                            $statusColor = 'full';
                        } elseif ($isFirstShift) {
                            $statusColor = 'first';
                        } elseif ($isSecondShift) {
                            $statusColor = 'second';
                        }

                        $colorClass = match($statusColor) {
                            'available' => 'bg-emerald-500/10 border-emerald-500/30 text-emerald-400 hover:bg-emerald-500/20',
                            'first' => 'bg-blue-500/10 border-blue-500/30 text-blue-400 hover:bg-blue-500/20',
                            'second' => 'bg-indigo-500/10 border-indigo-500/30 text-indigo-400 hover:bg-indigo-500/20',
                            'full' => 'bg-rose-500/10 border-rose-500/30 text-rose-400 hover:bg-rose-500/20',
                            'maintenance' => 'bg-amber-500/10 border-amber-500/30 text-amber-400 hover:bg-amber-500/20',
                            default => 'bg-gray-500/10 border-gray-500/30 text-gray-400',
                        };

                        $statusLabel = match($statusColor) {
                            'first' => '1st Shift',
                            'second' => '2nd Shift',
                            'full' => 'Occupied',
                            'maintenance' => 'Maintenance',
                            default => 'Available',
                        };
                    @endphp
                    
                    <div class="relative group cursor-pointer aspect-square rounded-xl border flex flex-col items-center justify-center transition-all duration-200 hover:scale-105 {{ $colorClass }}">
                        
                        <span class="font-bold text-base">{{ $seat->seat_number }}</span>
                        <span class="text-[10px] opacity-60 mt-1 uppercase tracking-tighter">{{ $statusLabel }}</span>
                        
                        <!-- Hover Menu -->
                        <div class="absolute inset-0 bg-black/95 backdrop-blur-sm rounded-xl flex flex-col items-center justify-center p-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-10 border border-gray-700">
                            <p class="text-[10px] text-gray-400 mb-2 font-medium">SET STATUS</p>
                            <form action="{{ route('seats.update', $seat) }}" method="POST" class="w-full">
                                @csrf @method('PUT')
                                <select name="status" onchange="this.form.submit()" class="w-full text-[10px] bg-gray-800 text-white rounded border border-gray-700 mb-2 focus:outline-none p-1 cursor-pointer">
                                    <option value="available" {{ $seat->status == 'available' ? 'selected' : '' }}>Available</option>
                                    <option value="maintenance" {{ $seat->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                </select>
                            </form>

                            <form action="{{ route('seats.destroy', $seat) }}" method="POST" class="w-full">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-[10px] w-full py-1 rounded bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition" onclick="return confirm('Delete this seat?')">Delete</button>
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
