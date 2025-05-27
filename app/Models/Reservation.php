<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Import the User model
use App\Models\Schedule; // Import the Schedule model

class Reservation extends Model // Changed class name from Reporting to Reservation
{
    use HasFactory;

    protected $table = 'reservations'; // Changed table name from 'reporting' to 'reservations'

    protected $fillable = [
        'user_id',          // Foreign key to the User who made the reservation
        'schedule_id',      // Foreign key to the specific bus Schedule
        'seat_number',      // Optional: The specific seat number (nullable)
        'status',           // e.g., 'pending', 'confirmed', 'cancelled', 'completed'
        'booking_reference',// A unique string for the reservation (e.g., BUS-XYZ123)
        'total_fare',       // The fare for this reservation
    ];

    /**
     * Get the user that owns the reservation.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the schedule that the reservation belongs to.
     */
    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    // You might also want to add accessors or other helper methods here
    // For example, to easily get the bus or route details via the schedule:
    /*
    public function bus()
    {
        return $this->hasOneThrough(Bus::class, Schedule::class);
    }

    public function route()
    {
        return $this->hasOneThrough(Route::class, Schedule::class);
    }
    */
}