<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms';

    protected $fillable = [
        'room_code',
        'room_name',
        'capacity',
        'building',
        'floor',
        'facilities',
        'is_active',
        'faculty_id',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'floor' => 'integer',
        'is_active' => 'boolean',
    ];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    /**
     * Course schedules using this room
     */
    public function courseSchedules()
    {
        return $this->hasMany(CourseSchedule::class, 'room_id');
    }

    /**
     * Scope for active rooms
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

