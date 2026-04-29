@extends('layouts.admin')

@section('title', 'Pending Payments')
@section('page_title', 'Students with Pending Payments')

@section('content')
<div class="p-4 md:p-8 space-y-6 md:space-y-8 flex-1">
    
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
        <div>
            <h2 class="text-xl md:text-3xl font-bold text-white">Pending Collections</h2>
            <p class="text-gray-400 text-sm">List of students who have yet to complete their payments or whose months have completed.</p>
        </div>
        <div class="bg-rose-500/10 border border-rose-500/30 px-4 md:px-6 py-2 md:py-3 rounded-xl md:rounded-2xl">
            <span class="text-gray-400 text-sm">Total Pending:</span>
            <span class="text-xl md:text-2xl font-bold ml-2 text-rose-500">{{ $bookings->count() }}</span>
        </div>
    </div>

    <!-- Pending List -->
    <div class="glass-panel rounded-xl md:rounded-3xl overflow-hidden shadow-2xl">
      
      <!-- Desktop Table (hidden on mobile) -->
      <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-white/5 border-b border-white/10">
                <tr>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-400">Student Name</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-400">Status</th>
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
                        <p class="text-xs text-gray-500">{{ $booking->student->phone_number }}</p>
                    </td>
                    <td class="px-6 py-6">
                        @if($booking->end_date < now()->toDateString())
                            <span class="bg-red-500/10 text-red-500 px-3 py-1 rounded-full text-xs font-bold border border-red-500/20">
                                Month Completed
                            </span>
                        @else
                            <span class="bg-amber-500/10 text-amber-500 px-3 py-1 rounded-full text-xs font-bold border border-amber-500/20">
                                Payment Pending
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-6">
                        <span class="bg-indigo-500/10 text-indigo-400 px-3 py-1 rounded-full text-xs font-bold border border-indigo-500/20">
                            Seat {{ $booking->seat ? $booking->seat->seat_number : 'Deleted' }}
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
                        <div class="flex flex-col gap-2 items-end">
                            <form action="{{ route('bookings.pay', $booking) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2 rounded-xl text-xs font-bold transition-all">
                                    Payment Done
                                </button>
                            </form>
                            <a href="{{ route('bookings.index') }}" class="text-xs text-gray-500 hover:text-white underline">View Details</a>
                        </div>
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

      <!-- Mobile Cards (hidden on desktop) -->
      <div class="md:hidden divide-y divide-white/5">
        @forelse($bookings as $booking)
        <div class="p-4 hover:bg-white/5 transition">
            <!-- Header: Student + Amount -->
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-rose-500/20 border border-rose-500/30 flex items-center justify-center text-rose-400 font-bold text-sm">
                        {{ substr($booking->student->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-bold text-white text-sm">{{ $booking->student->name }}</p>
                        <p class="text-[11px] text-gray-500">{{ $booking->student->phone_number }}</p>
                    </div>
                </div>
                <p class="font-bold text-rose-500 text-lg">₹{{ number_format($booking->total_amount, 2) }}</p>
            </div>

            <!-- Status Label Mobile -->
            <div class="mb-3">
                @if($booking->end_date < now()->toDateString())
                    <span class="bg-red-500/10 text-red-500 px-3 py-1 rounded-full text-[10px] font-bold border border-red-500/20">
                        Month Completed
                    </span>
                @else
                    <span class="bg-amber-500/10 text-amber-500 px-3 py-1 rounded-full text-[10px] font-bold border border-amber-500/20">
                        Payment Pending
                    </span>
                @endif
            </div>

            <!-- Details Grid -->
            <div class="grid grid-cols-2 gap-2 text-xs mb-3">
                <div class="bg-white/5 rounded-lg p-2">
                    <p class="text-gray-500 mb-0.5">💺 Seat</p>
                    <p class="text-indigo-400 font-bold">{{ $booking->seat ? $booking->seat->seat_number : 'Deleted' }}</p>
                </div>
                <div class="bg-white/5 rounded-lg p-2">
                    <p class="text-gray-500 mb-0.5">⏰ Shift</p>
                    <p class="text-gray-300">{{ $booking->shift_type }}</p>
                </div>
                <div class="bg-white/5 rounded-lg p-2 col-span-2">
                    <p class="text-gray-500 mb-0.5">📅 Duration</p>
                    <p class="text-gray-300">{{ $booking->start_date->format('M d, Y') }} → {{ $booking->end_date->format('M d, Y') }}</p>
                </div>
            </div>

            <!-- Action Mobile -->
            <div class="flex gap-2">
                <form action="{{ route('bookings.pay', $booking) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2 rounded-xl text-xs font-bold transition-all">
                        Payment Done
                    </button>
                </form>
                <a href="{{ route('bookings.index') }}" class="px-4 py-2 bg-white/5 rounded-xl text-xs flex items-center justify-center">
                    View
                </a>
            </div>
        </div>
        @empty
        <div class="p-8 text-center text-gray-500">
            <p class="text-lg font-medium">No pending payments found!</p>
            <p class="text-sm">All student accounts are currently settled.</p>
        </div>
        @endforelse
      </div>
    </div>

</div>
@endsection
