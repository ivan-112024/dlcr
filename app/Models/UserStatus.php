<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStatus extends Model
{
    use HasFactory;

    // If your table name is not 'user_statuses', specify it here:
    // protected $table = 'user_status';

    // The attributes that are mass assignable
    protected $fillable = ['status_name'];

    // Hide timestamps if you want (optional)
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    // Cast timestamps to datetime objects
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
