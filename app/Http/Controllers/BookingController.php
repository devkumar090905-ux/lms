<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Booking;
use App\Models\Student;
use App\Models\Seat;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with('student', 'seat')->orderBy('created_at', 'desc')->get();
        $students = Student::orderBy('name')->get();
        // Only show seats that are available
        $availableSeats = Seat::where('status', 'available')->orderBy('seat_number')->get();
        
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

        $seat = Seat::find($request->seat_id);
        if ($seat->status !== 'available') {
            return redirect()->back()->with('error', 'The selected seat is no longer available.');
        }

        // Create booking
        Booking::create($request->all());

        // Mark seat as occupied
        $seat->update(['status' => 'occupied']);

        return redirect()->back()->with('success', 'Seat booked successfully!');
    }

    public function destroy(Booking $booking)
    {
        // Change seat status back to available
        if ($booking->seat) {
            $booking->seat->update(['status' => 'available']);
        }
        
        $booking->delete();
        return redirect()->back()->with('success', 'Booking closed and seat is now available!');
    }
}
