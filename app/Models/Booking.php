<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'student_id', 'seat_id', 'shift_type', 'start_date', 'end_date', 'payment_status', 'total_amount'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }
}
