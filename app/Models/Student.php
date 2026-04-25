<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['library_id', 'name', 'phone_number', 'email', 'address'];

    public function library()
    {
        return $this->belongsTo(LibrarySetting::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
