<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripProcessing extends Model
{
    use HasFactory;

    protected $table = 'trip_processing';

    protected $fillable = [
        'trip_id',
        'status',
        'started_at',
        'ended_at',
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class, 'trip_id');
    }
}
