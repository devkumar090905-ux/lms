<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Booking;
use App\Models\Student;
use App\Models\Seat;
use Illuminate\Support\Facades\Session;

class BookingController extends Controller
{
    public function index()
    {
        $libraryId = Session::get('library_id');
        $bookings = Booking::with('student', 'seat')->where('library_id', $libraryId)->orderBy('created_at', 'desc')->get();
        $students = Student::where('library_id', $libraryId)->orderBy('name')->get();
        
        // Fetch seats and filter based on shift availability
        $allSeats = Seat::where('library_id', $libraryId)
            ->orderByRaw('LENGTH(seat_number) ASC')
            ->orderBy('seat_number', 'ASC')
            ->get();
            
        $availableSeats = $allSeats->filter(function($seat) {
            $activeBookings = $seat->bookings()->where('end_date', '>=', now()->toDateString())->get();
            
            // If no active bookings, it's available
            if ($activeBookings->isEmpty()) return true;
            
            // If there's a Full-time booking, it's NOT available
            if ($activeBookings->where('shift_type', 'Full-time')->isNotEmpty()) return false;
            
            // If there's only one shift booked (either First or Second), it's available for the other
            if ($activeBookings->count() < 2) return true;
            
            return false;
        });
        
        return view('admin.bookings.index', compact('bookings', 'students', 'availableSeats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'seat_id' => 'required|exists:seats,id',
            'shift_type' => 'required|in:First,Second,Full-time',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'payment_status' => 'required|in:Paid,Pending',
            'total_amount' => 'required|numeric|min:0',
        ]);

        $libraryId = Session::get('library_id');
        $seat = Seat::where('id', $request->seat_id)->where('library_id', $libraryId)->firstOrFail();

        // Check for conflicting active bookings within the requested date range
        $activeBookings = $seat->bookings()
            ->where('start_date', '<=', $request->end_date)
            ->where('end_date', '>=', $request->start_date)
            ->get();

        foreach ($activeBookings as $booking) {
            // Conflict if existing booking is Full-time
            if ($booking->shift_type === 'Full-time') {
                return redirect()->back()->with('error', 'This seat is already booked Full-time for the selected period.');
            }
            // Conflict if new booking is Full-time and seat already has any booking
            if ($request->shift_type === 'Full-time') {
                return redirect()->back()->with('error', 'This seat is already partially booked. Full-time booking is not possible.');
            }
            // Conflict if shifts are the same
            if ($booking->shift_type === $request->shift_type) {
                return redirect()->back()->with('error', "This seat is already booked for $request->shift_type Shift.");
            }
        }

        // Create booking
        $data = $request->all();
        $data['library_id'] = $libraryId;
        Booking::create($data);

        // Update seat status based on occupancy
        $updatedActiveBookings = $seat->bookings()->where('end_date', '>=', now()->toDateString())->get();
        if ($updatedActiveBookings->where('shift_type', 'Full-time')->isNotEmpty() || $updatedActiveBookings->count() >= 2) {
            $seat->update(['status' => 'occupied']);
        } else {
            $seat->update(['status' => 'available']);
        }

        return redirect()->back()->with('success', 'Seat booked successfully!');
    }

    public function destroy(Booking $booking)
    {
        $seat = $booking->seat;
        $booking->delete();

        // Update seat status based on remaining active bookings
        if ($seat) {
            $remainingBookings = $seat->bookings()->where('end_date', '>=', now()->toDateString())->get();
            if ($remainingBookings->where('shift_type', 'Full-time')->isEmpty() && $remainingBookings->count() < 2) {
                $seat->update(['status' => 'available']);
            } else {
                $seat->update(['status' => 'occupied']);
            }
        }
        
        return redirect()->back()->with('success', 'Booking closed and seat status updated!');
    }

    public function markAsPaid(Booking $booking)
    {
        // If the month is complete, renew it for another month
        if ($booking->end_date < now()->toDateString()) {
            $newStartDate = now()->toDateString();
            $newEndDate = now()->addMonth()->toDateString();
            
            $booking->update([
                'payment_status' => 'Paid',
                'start_date' => $newStartDate,
                'end_date' => $newEndDate
            ]);
            
            return redirect()->back()->with('success', 'Payment marked as Paid and Booking renewed for another month!');
        }

        // If it was just pending, mark as paid
        $booking->update(['payment_status' => 'Paid']);
        return redirect()->back()->with('success', 'Payment marked as Paid!');
    }
}
