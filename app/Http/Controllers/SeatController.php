<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Seat;

class SeatController extends Controller
{
    public function index()
    {
        $seats = Seat::orderBy('seat_number')->get();
        return view('admin.seats.index', compact('seats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'prefix' => 'nullable|string|max:10',
            'start_number' => 'required|integer|min:1',
            'end_number' => 'required|integer|min:1|gte:start_number',
        ]);

        $prefix = $request->input('prefix', '');
        $start = $request->input('start_number');
        $end = $request->input('end_number');

        $seatsToInsert = [];
        for ($i = $start; $i <= $end; $i++) {
            $seatNumber = $prefix . $i;
            // Check if exists to avoid bulk insert crashes
            if (!Seat::where('seat_number', $seatNumber)->exists()) {
                $seatsToInsert[] = [
                    'seat_number' => $seatNumber,
                    'status' => 'available',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (count($seatsToInsert) > 0) {
            Seat::insert($seatsToInsert);
            return redirect()->back()->with('success', count($seatsToInsert) . ' seats added successfully!');
        }

        return redirect()->back()->with('error', 'No new seats were added (they might already exist).');
    }

    public function update(Request $request, Seat $seat)
    {
        $request->validate([
            'status' => 'required|in:available,occupied,maintenance',
        ]);

        $seat->update(['status' => $request->status]);
        
        return redirect()->back()->with('success', 'Seat status updated!');
    }

    public function destroy(Seat $seat)
    {
        $seat->delete();
        return redirect()->back()->with('success', 'Seat removed successfully!');
    }
}
