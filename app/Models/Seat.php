<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    protected $fillable = ['library_id', 'seat_number', 'status'];

    public function library()
    {
        return $this->belongsTo(LibrarySetting::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
