<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LibrarySetting extends Model
{
    protected $fillable = [
        'owner_name',
        'library_name',
        'address',
        'total_seats',
        'opening_time',
        'closing_time',
        'email',
        'mobile_number',
        'password',
        'is_active',
        'alert_message',
    ];

    public function seats()
    {
        return $this->hasMany(Seat::class, 'library_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'library_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'library_id');
    }
}
