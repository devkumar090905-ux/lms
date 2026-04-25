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
        
        return view('admin.dashboard', compact(
            'library', 
            'totalSeatsCount', 
            'availableSeats', 
            'totalStudents', 
            'activeBookings'
        ));
    }
}
