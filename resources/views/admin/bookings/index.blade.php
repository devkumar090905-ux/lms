@extends('layouts.admin')

@section('title', 'Manage Bookings')
@section('page_title', 'Seat Bookings')

@section('content')
<div class="p-4 md:p-8 space-y-6 md:space-y-8 flex-1">
    
    <!-- Add Booking Section -->
    <div class="glass-panel p-4 md:p-6 rounded-xl md:rounded-2xl">
        <h3 class="text-lg md:text-xl font-semibold text-white mb-4">Create New Booking</h3>
        
        <form action="{{ route('bookings.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
            @csrf
            
            <div class="flex flex-col">
                <label for="student_id" class="text-sm text-gray-400 mb-1">Select Student *</label>
                <div class="space-y-2">
                    <input type="text" id="studentSearchInput" onkeyup="filterBookingStudents()" placeholder="Search name or ID..." class="w-full bg-white/5 border border-gray-700 rounded-lg px-4 py-1.5 text-xs text-white focus:border-indigo-500 outline-none transition-all" autocomplete="off">
                    <select name="student_id" id="student_id" class="bg-black/50 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-blue-500 w-full" required>
                        <option value="" disabled selected>Choose a student...</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">{{ $student->name }} (ID: {{ $student->id }}) - {{ $student->phone_number }}</option>
                        @endforeach
                    </select>
                </div>
                @error('student_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="flex flex-col">
                <label for="seat_id" class="text-sm text-gray-400 mb-1">Select Available Seat *</label>
                <select name="seat_id" id="seat_id" class="bg-black/50 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-blue-500" required>
                    <option value="" disabled selected>Choose a seat...</option>
                    @foreach($availableSeats as $seat)
                        @php
                            $activeShift = $seat->bookings()->where('end_date', '>=', now()->toDateString())->first();
                            $statusText = $activeShift ? "({$activeShift->shift_type} Occupied)" : "(Available)";
                        @endphp
                        <option value="{{ $seat->id }}" class="{{ $activeShift ? 'text-amber-400' : 'text-emerald-400' }}" {{ old('seat_id') == $seat->id ? 'selected' : '' }}>
                            {{ $seat->seat_number }} {{ $statusText }}
                        </option>
                    @endforeach
                </select>
                @error('seat_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="flex flex-col">
                <label for="shift_type" class="text-sm text-gray-400 mb-1">Shift Type *</label>
                <select name="shift_type" id="shift_type" class="bg-black/50 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-blue-500" required>
                    <option value="First" {{ old('shift_type') == 'First' ? 'selected' : '' }}>First Shift</option>
                    <option value="Second" {{ old('shift_type') == 'Second' ? 'selected' : '' }}>Second Shift</option>
                    <option value="Full-time" {{ old('shift_type') == 'Full-time' ? 'selected' : '' }}>Full-time</option>
                </select>
                @error('shift_type') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            
            <div class="flex flex-col">
                <label for="total_amount" class="text-sm text-gray-400 mb-1">Price / Total Amount *</label>
                <input type="number" step="0.01" name="total_amount" id="total_amount" class="bg-black/50 border border-gray-700 rounded-lg px-4 py-2 text-white placeholder-gray-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" placeholder="1000.00" value="{{ old('total_amount') }}" required>
                @error('total_amount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="flex flex-col">
                <label for="start_date" class="text-sm text-gray-400 mb-1">Start Date *</label>
                <input type="date" name="start_date" id="start_date" class="bg-black/50 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-blue-500" value="{{ old('start_date', date('Y-m-d')) }}" required>
                @error('start_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="flex flex-col">
                <label for="end_date" class="text-sm text-gray-400 mb-1">End Date *</label>
                <input type="date" name="end_date" id="end_date" class="bg-black/50 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-blue-500" value="{{ old('end_date', date('Y-m-d', strtotime('+30 days'))) }}" required>
                @error('end_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="flex flex-col">
                <label for="payment_status" class="text-sm text-gray-400 mb-1">Payment Status *</label>
                <select name="payment_status" id="payment_status" class="bg-black/50 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-blue-500" required>
                    <option value="Paid" class="text-emerald-400" {{ old('payment_status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                    <option value="Pending" class="text-amber-400" {{ old('payment_status', 'Pending') == 'Pending' ? 'selected' : '' }}>Pending</option>
                </select>
                @error('payment_status') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-medium px-4 py-2 rounded-lg shadow-lg shadow-blue-500/20 transition-all border border-blue-500">
                    Create Booking
                </button>
            </div>
        </form>
    </div>

    <!-- Bookings List -->
    <div class="glass-panel rounded-xl md:rounded-2xl overflow-hidden">
        <div class="px-4 md:px-6 py-4 border-b border-gray-800">
            <h3 class="text-lg md:text-xl font-semibold text-white">All Bookings</h3>
        </div>
        
        <!-- Desktop Table (hidden on mobile) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-black/20 text-gray-400 text-sm">
                        <th class="px-6 py-3 font-medium border-b border-gray-800">Seat</th>
                        <th class="px-6 py-3 font-medium border-b border-gray-800">Student</th>
                        <th class="px-6 py-3 font-medium border-b border-gray-800">Shift</th>
                        <th class="px-6 py-3 font-medium border-b border-gray-800">Duration</th>
                        <th class="px-6 py-3 font-medium border-b border-gray-800">Payment</th>
                        <th class="px-6 py-3 font-medium border-b border-gray-800 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-300">
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-white/5 border-b border-gray-800/50 transition">
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center justify-center px-3 py-1 rounded bg-blue-500/20 text-blue-400 font-bold border border-blue-500/30">
                                {{ $booking->seat ? $booking->seat->seat_number : 'Deleted' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-medium text-white">
                            {{ $booking->student ? $booking->student->name : 'Deleted Student' }}
                        </td>
                        <td class="px-6 py-4">{{ $booking->shift_type }}</td>
                        <td class="px-6 py-4 text-sm">
                            <div class="text-gray-400">{{ $booking->start_date->format('M d, Y') }}</div>
                            <div>to <span class="{{ $booking->end_date < now()->toDateString() ? 'text-red-400 font-bold' : 'text-emerald-400' }}">{{ $booking->end_date->format('M d, Y') }}</span></div>
                            @if($booking->end_date < now()->toDateString())
                                <span class="text-[10px] text-red-500 uppercase font-bold">Month Completed</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div>₹{{ number_format($booking->total_amount, 2) }}</div>
                            @if($booking->payment_status == 'Paid' && $booking->end_date >= now()->toDateString())
                                <span class="text-xs bg-emerald-500/20 text-emerald-400 px-2 py-0.5 rounded border border-emerald-500/30">Paid</span>
                            @else
                                <div class="flex flex-col gap-1">
                                    <span class="text-xs bg-amber-500/20 text-amber-400 px-2 py-0.5 rounded border border-amber-500/30 w-fit">Pending</span>
                                    <form action="{{ route('bookings.pay', $booking) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-[10px] bg-emerald-600 hover:bg-emerald-500 text-white px-2 py-1 rounded transition-all">
                                            Mark as Paid
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('bookings.destroy', $booking) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to end this booking? The seat will be marked as available again.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300">End & Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            No active bookings found. Select a student and a seat above to create one.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards (hidden on desktop) -->
        <div class="md:hidden divide-y divide-gray-800/50">
            @forelse($bookings as $booking)
            <div class="p-4 hover:bg-white/5 transition">
                <!-- Header: Seat + Student -->
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg bg-blue-500/20 text-blue-400 font-bold text-sm border border-blue-500/30">
                            {{ $booking->seat ? $booking->seat->seat_number : 'Deleted' }}
                        </span>
                        <div>
                            <p class="font-medium text-white text-sm">{{ $booking->student ? $booking->student->name : 'Deleted Student' }}</p>
                            <p class="text-[11px] text-gray-500">{{ $booking->shift_type }} Shift</p>
                        </div>
                    </div>
                    @if($booking->payment_status == 'Paid' && $booking->end_date >= now()->toDateString())
                        <span class="text-[11px] bg-emerald-500/20 text-emerald-400 px-2.5 py-1 rounded-lg border border-emerald-500/30 font-medium">Paid</span>
                    @else
                        <span class="text-[11px] bg-amber-500/20 text-amber-400 px-2.5 py-1 rounded-lg border border-amber-500/30 font-medium">Pending</span>
                    @endif
                </div>
                
                <!-- Status Row -->
                @if($booking->end_date < now()->toDateString())
                <div class="mb-3">
                    <span class="bg-red-500/10 text-red-500 px-3 py-1 rounded-full text-[10px] font-bold border border-red-500/20 uppercase">
                        Month Completed
                    </span>
                </div>
                @endif

                <!-- Details Grid -->
                <div class="grid grid-cols-2 gap-2 text-xs mb-3">
                    <div class="bg-white/5 rounded-lg p-2">
                        <p class="text-gray-500 mb-0.5">📅 Start Date</p>
                        <p class="text-gray-300">{{ $booking->start_date->format('M d, Y') }}</p>
                    </div>
                    <div class="bg-white/5 rounded-lg p-2">
                        <p class="text-gray-500 mb-0.5">📅 End Date</p>
                        <p class="{{ $booking->end_date < now()->toDateString() ? 'text-red-400' : 'text-emerald-400' }}">{{ $booking->end_date->format('M d, Y') }}</p>
                    </div>
                </div>

                <!-- Footer: Amount + Action -->
                <div class="flex items-center justify-between gap-2">
                    <p class="text-white font-bold text-sm">₹{{ number_format($booking->total_amount, 2) }}</p>
                    <div class="flex items-center gap-2">
                        @if($booking->payment_status == 'Pending' || $booking->end_date < now()->toDateString())
                            <form action="{{ route('bookings.pay', $booking) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-all border border-emerald-500">
                                    Pay Done
                                </button>
                            </form>
                        @endif
                        <form action="{{ route('bookings.destroy', $booking) }}" method="POST" onsubmit="return confirm('Are you sure you want to end this booking?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-300 text-xs bg-red-500/10 px-3 py-1.5 rounded-lg border border-red-500/20">End</button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-8 text-center text-gray-500">
                No active bookings found. Select a student and a seat above to create one.
            </div>
            @endforelse
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function filterBookingStudents() {
    let input = document.getElementById('studentSearchInput');
    let filter = input.value.toLowerCase().replace('#', '').trim();
    let select = document.getElementById('student_id');
    let options = select.getElementsByTagName('option');
    let exactMatchFound = false;

    for (let i = 1; i < options.length; i++) {
        let optionText = options[i].text.toLowerCase();
        let optionValue = options[i].value; // This is the ID
        
        // Check if text matches search
        if (optionText.includes(filter)) {
            options[i].style.display = "";
            
            // Auto-select if it's an exact ID match and nothing has been selected yet
            if (filter !== "" && optionValue === filter && !exactMatchFound) {
                select.value = optionValue;
                exactMatchFound = true;
            }
        } else {
            options[i].style.display = "none";
        }
    }
}
</script>
@endpush
