<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seat;
use App\Models\Student;
use App\Models\Booking;
use App\Models\LibrarySetting;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function index()
    {
        if (!Session::has('library_id')) {
            return redirect()->route('home');
        }

        $library = LibrarySetting::find(Session::get('library_id'));
        
        if (!$library) {
            Session::forget(['library_id', 'library_name', 'owner_name']);
            return redirect()->route('home')->withErrors(['error' => 'Session expired or Library not found. Please login again.']);
        }
        
        $totalSeatsCount = Seat::where('library_id', $library->id)->count();
        $availableSeats = Seat::where('library_id', $library->id)->where('status', 'available')->count();
        $totalStudents = Student::where('library_id', $library->id)->count();
        $activeBookings = Booking::where('library_id', $library->id)->where('end_date', '>=', now())->count();
        $pendingPaymentsCount = Booking::where('library_id', $library->id)
            ->where(function($query) {
                $query->where('payment_status', 'Pending')
                      ->orWhere('end_date', '<', now()->toDateString());
            })
            ->distinct('student_id')
            ->count('student_id');
        
        return view('admin.dashboard', compact(
            'library', 
            'totalSeatsCount', 
            'availableSeats', 
            'totalStudents', 
            'activeBookings',
            'pendingPaymentsCount'
        ));
    }

    public function pendingPayments()
    {
        if (!Session::has('library_id')) {
            return redirect()->route('home');
        }

        $library = LibrarySetting::find(Session::get('library_id'));

        // Fetch bookings with 'Pending' status OR those whose end_date has passed
        $bookings = Booking::with('student', 'seat')
            ->where('library_id', $library->id)
            ->where(function($query) {
                $query->where('payment_status', 'Pending')
                      ->orWhere('end_date', '<', now()->toDateString());
            })
            ->get();

        return view('admin.pending_payments', compact('library', 'bookings'));
    }
}
