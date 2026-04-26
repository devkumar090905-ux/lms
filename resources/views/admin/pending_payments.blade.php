@extends('layouts.admin')

@section('title', 'Pending Payments')
@section('page_title', 'Students with Pending Payments')

@section('content')
<div class="p-4 md:p-8 space-y-6 md:space-y-8 flex-1">
    
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
        <div>
            <h2 class="text-2xl md:text-3xl font-bold text-white">Pending Collections</h2>
            <p class="text-gray-400 text-sm">List of students who have yet to complete their payments for assigned seats.</p>
        </div>
        <div class="bg-rose-500/10 border border-rose-500/30 px-4 md:px-6 py-2 md:py-3 rounded-xl md:rounded-2xl">
            <span class="text-gray-400 text-sm">Total Pending:</span>
            <span class="text-xl md:text-2xl font-bold ml-2 text-rose-500">{{ $bookings->count() }}</span>
        </div>
    </div>

    <!-- Pending List Table -->
    <div class="glass-panel rounded-2xl md:rounded-3xl overflow-hidden shadow-2xl">
      <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-white/5 border-b border-white/10">
                <tr>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-400">Student Name</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-400">Contact</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-400">Seat</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-400 text-center">Duration</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-400">Due Amount</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-400 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($bookings as $booking)
                <tr class="hover:bg-white/5 transition-all group">
                    <td class="px-6 py-6">
                        <p class="font-bold text-lg text-white group-hover:text-rose-400 transition-colors">{{ $booking->student->name }}</p>
                    </td>
                    <td class="px-6 py-6">
                        <p class="text-sm text-gray-300">{{ $booking->student->phone_number }}</p>
                        <p class="text-xs text-gray-500">{{ $booking->student->email ?? 'No Email' }}</p>
                    </td>
                    <td class="px-6 py-6">
                        <span class="bg-indigo-500/10 text-indigo-400 px-3 py-1 rounded-full text-xs font-bold border border-indigo-500/20">
                            Seat {{ $booking->seat->seat_number }}
                        </span>
                    </td>
                    <td class="px-6 py-6 text-center text-sm text-gray-400">
                        {{ $booking->start_date->format('M d, Y') }} to {{ $booking->end_date->format('M d, Y') }}
                        <p class="text-[10px] text-indigo-400 uppercase font-bold mt-1">{{ $booking->shift_type }}</p>
                    </td>
                    <td class="px-6 py-6 font-bold text-rose-500">
                        ₹{{ number_format($booking->total_amount, 2) }}
                    </td>
                    <td class="px-6 py-6 text-right">
                        <a href="{{ route('bookings.index') }}" class="inline-block bg-white/5 hover:bg-emerald-600 px-4 py-2 rounded-xl text-sm transition-all" title="Manage Payment">
                            View Booking
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <p class="text-xl font-medium">No pending payments found!</p>
                        <p class="text-sm">All student accounts are currently settled.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
      </div>
    </div>

</div>
@endsection
