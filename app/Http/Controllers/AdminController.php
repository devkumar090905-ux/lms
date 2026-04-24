<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Seat;
use App\Models\Student;
use App\Models\Booking;

class AdminController extends Controller
{
    public function index()
    {
        $totalSeats = Seat::count();
        $availableSeats = Seat::where('status', 'available')->count();
        $totalStudents = Student::count();
        $activeBookings = Booking::where('end_date', '>=', now())->count();
        
        return view('admin.dashboard', compact('totalSeats', 'availableSeats', 'totalStudents', 'activeBookings'));
    }
}
