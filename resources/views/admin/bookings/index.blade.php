@extends('layouts.admin')

@section('title', 'Manage Bookings')
@section('page_title', 'Seat Bookings')

@section('content')
<div class="p-8 space-y-8 flex-1">
    
    <!-- Add Booking Section -->
    <div class="glass-panel p-6 rounded-2xl">
        <h3 class="text-xl font-semibold text-white mb-4">Create New Booking</h3>
        
        <form action="{{ route('bookings.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
            @csrf
            
            <div class="flex flex-col">
                <label for="student_id" class="text-sm text-gray-400 mb-1">Select Student *</label>
                <select name="student_id" id="student_id" class="bg-black/50 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-blue-500" required>
                    <option value="" disabled selected>Choose a student...</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>{{ $student->name }} ({{ $student->phone_number }})</option>
                    @endforeach
                </select>
                @error('student_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="flex flex-col">
                <label for="seat_id" class="text-sm text-gray-400 mb-1">Select Available Seat *</label>
                <select name="seat_id" id="seat_id" class="bg-black/50 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-blue-500" required>
                    <option value="" disabled selected>Choose a seat...</option>
                    @foreach($availableSeats as $seat)
                        <option value="{{ $seat->id }}" class="text-emerald-400" {{ old('seat_id') == $seat->id ? 'selected' : '' }}>{{ $seat->seat_number }} (Available)</option>
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
    <div class="glass-panel rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-800">
            <h3 class="text-xl font-semibold text-white">All Bookings</h3>
        </div>
        
        <div class="overflow-x-auto">
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
                            <div>to <span class="{{ $booking->end_date < now() ? 'text-red-400' : 'text-emerald-400' }}">{{ $booking->end_date->format('M d, Y') }}</span></div>
                        </td>
                        <td class="px-6 py-4">
                            <div>₹{{ number_format($booking->total_amount, 2) }}</div>
                            @if($booking->payment_status == 'Paid')
                                <span class="text-xs bg-emerald-500/20 text-emerald-400 px-2 py-0.5 rounded border border-emerald-500/30">Paid</span>
                            @else
                                <span class="text-xs bg-amber-500/20 text-amber-400 px-2 py-0.5 rounded border border-amber-500/30">Pending</span>
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
    </div>

</div>
@endsection
