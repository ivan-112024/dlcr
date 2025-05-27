<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = ['destination', 'trip_date'];

    // Relationship: a trip has many processing records
    public function processings()
    {
        return $this->hasMany(TripProcessing::class, 'trip_id');
    }
}
